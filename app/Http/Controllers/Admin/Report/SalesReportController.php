<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Sales;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesReportController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = Sales::query()
                ->with([
                    'pharmacies:id_apotek,id_sales,id_apotek',
                    'pharmacies.products:id,pharmacy_id,stock,stock_sold,product_id',
                    'pharmacies.products.product:id,harga'
                ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('products_sum_stock', function ($item) {
                    $stockSum = $item->pharmacies->sum(function ($subitem) {
                        return $subitem->products->sum('stock');
                    });

                    return $stockSum;
                })
                ->addColumn('products_sum_stock_sold', function ($item) {
                    $stockSum = $item->pharmacies->sum(function ($subitem) {
                        return $subitem->products->sum('stock_sold');
                    });

                    return $stockSum;
                })
                ->addColumn('products_price_stock', function ($item) {
                    $sumPrice = $item->pharmacies->sum(function ($subitem) {
                        return $subitem->products->sum(function ($subsub) {
                            return $subsub->stock * $subsub->product->harga;
                        });
                    });
                    return 'Rp. ' . number_format($sumPrice);
                })
                ->addColumn('products_price_stock_sold', function ($item) {
                    $sumPrice = $item->pharmacies->sum(function ($subitem) {
                        return $subitem->products->sum(function ($subsub) {
                            return $subsub->stock_sold * $subsub->product->harga;
                        });
                    });
                    return 'Rp. ' . number_format($sumPrice);
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.sales-report.show', $item);
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
        return view('admin.report.sales-report.index');
    }
}
