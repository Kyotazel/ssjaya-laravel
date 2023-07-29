<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\DepositReportPharmacy;
use App\Models\OngoingRequestPharmacy;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PharmacyReportController extends Controller
{
    public function index()
    {

        $sales = request()->sales;

        if (request()->wantsJson()) {
            $query = Pharmacy::query()->with([
                'products',
                'products.product:id,harga',
                'sales'
            ])
                ->when(isset(request()->sales), function ($q) {
                    $q->where('id_sales', request()->sales);
                })
                ->withSum('products', 'stock')
                ->withSum('products', 'stock_sold');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('products_price_stock', function ($item) {
                    $sumPrice = $item->products->sum(function ($subitem) {
                        return $subitem->stock * $subitem->product->harga;
                    });

                    return 'Rp. ' . number_format($sumPrice);
                })
                ->addColumn('products_price_stock_sold', function ($item) {
                    $sumPrice = $item->products->sum(function ($subitem) {
                        return $subitem->stock_sold * $subitem->product->harga;
                    });

                    return 'Rp. ' . number_format($sumPrice);
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.pharmacy-report.show', $item);
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                    <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                </div>
                            </div>";
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('admin.report.pharmacy-report.index', get_defined_vars());
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::with([
            'sales',
            'city',
            'products.product:id,nama,harga'
        ])
            ->withSum('products', 'stock')
            ->withSum('products', 'stock_sold')
            ->find($id);

        $pharmacy->products = $pharmacy->products->map(function ($product) {
            $product->price_stock = $product->stock * $product->product->harga;
            $product->price_stock_sold = $product->stock_sold * $product->product->harga;

            return $product;
        });

        $pharmacy->products_price_stock = $pharmacy->products->sum('price_stock');
        $pharmacy->products_price_stock_sold = $pharmacy->products->sum('price_stock_sold');

        return view('admin.report.pharmacy-report.detail', get_defined_vars());
    }

    public function log($id)
    {

        if (request()->wantsJson()) {
            $raw = DB::raw("(SELECT * FROM (
                (SELECT orpp.id, orpp.stock, orpp.created_at r_ca, op.harga, op.nama, 'produk keluar' as type_trans FROM ongoing_request_pharmacy_products orpp
                LEFT JOIN ongoing_request_pharmacies orp ON orp.id = orpp.ongoing_request_pharmacy_id
                LEFT JOIN pharmacy_products pp ON pp.id = orp.pharmacy_id
                LEFT JOIN blw_produk op ON op.id = pp.product_id
                WHERE orp.pharmacy_id = $id)
                UNION ALL
                (SELECT drpp.id, drpp.stock, drpp.created_at r_ca, dp.harga, dp.nama, 'setoran barang' as type_trans FROM deposit_report_pharmacy_products drpp
                LEFT JOIN deposit_report_pharmacies drp ON drp.id = drpp.deposit_report_pharmacy_id
                LEFT JOIN pharmacy_products pp ON pp.id = drp.pharmacy_id
                LEFT JOIN blw_produk dp ON dp.id = pp.product_id
                WHERE drp.pharmacy_id = $id)
            ) AS unioned_data ORDER BY r_ca DESC)");

            $query = DB::select($raw);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('r_ca', function ($item) {
                    return carbonParse($item->r_ca)->format('d M Y H:i:s');
                })
                ->editColumn('harga', function ($item) {
                    return 'Rp. ' . number_format($item->stock * $item->harga);
                })
                ->toJson();
        }

        $pharmacy = Pharmacy::with([
            'products.product:id,nama,harga'
        ])
            ->withSum('products', 'stock')
            ->withSum('products', 'stock_sold')
            ->find($id);

        $pharmacy->products = $pharmacy->products->map(function ($product) {
            $product->price_stock = $product->stock * $product->product->harga;
            $product->price_stock_sold = $product->stock_sold * $product->product->harga;

            return $product;
        });

        $pharmacy->products_price_stock = $pharmacy->products->sum('price_stock');
        $pharmacy->products_price_stock_sold = $pharmacy->products->sum('price_stock_sold');

        return view('admin.report.pharmacy-report.log', get_defined_vars());
    }
}
