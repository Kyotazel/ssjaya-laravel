<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PharmacyReportController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = Pharmacy::query()->with([
                'products',
                'products.product' => function ($q) {
                    return $q->select(['id', 'harga']);
                },
                'sales'
            ])
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
        return view('admin.report.pharmacy-report.index');
    }
}
