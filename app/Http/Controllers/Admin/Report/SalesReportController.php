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
                    'pharmacies.products:id,pharmacy_id,stock,stock_sold,product_id',
                    'pharmacies.products.product:id,harga'
                ])
                ->with('pharmacies', function ($q) {
                    $q->withSum('products', 'stock')
                        ->withSum('products', 'stock_sold')
                        ->withSum('products', 'price_stock')
                        ->withSum('products', 'price_stock_sold')
                        ->having('products_sum_price_stock_sold', '<>', 0)
                        ->orHaving('products_sum_price_stock', '<>', 0);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('products_sum_stock', function ($item) {
                    $stockSum = $item->pharmacies->sum('products_sum_stock');

                    return $stockSum;
                })
                ->addColumn('products_sum_stock_sold', function ($item) {
                    $stockSum = $item->pharmacies->sum('products_sum_stock_sold');

                    return $stockSum;
                })
                ->addColumn('products_price_stock', function ($item) {
                    $sumPrice = $item->pharmacies->sum('products_sum_price_stock');
                    return 'Rp. ' . number_format($sumPrice);
                })
                ->addColumn('products_price_stock_sold', function ($item) {
                    $sumPrice = $item->pharmacies->sum('products_sum_price_stock_sold');
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

    public function show($id)
    {
        $sales = Sales::query()
            ->with([
                'pharmacies.products:id,pharmacy_id,stock,stock_sold,product_id,price_stock,price_stock_sold',
                'pharmacies.products.product:id,harga,nama'
            ])
            ->with('pharmacies', function ($q) {
                $q->withSum('products', 'stock')
                    ->withSum('products', 'stock_sold')
                    ->withSum('products', 'price_stock')
                    ->withSum('products', 'price_stock_sold')
                    ->having('products_sum_price_stock_sold', '<>', 0)
                    ->orHaving('products_sum_price_stock', '<>', 0);
            })
            ->withCount('pharmacies')
            ->find($id);

        // return $sales;

        $sales->products_sum_stock = $sales->pharmacies->sum('products_sum_stock');
        $sales->products_sum_stock_sold = $sales->pharmacies->sum('products_sum_stock_sold');
        $sales->products_price_stock = $sales->pharmacies->sum('products_sum_price_stock');
        $sales->products_price_stock_sold = $sales->pharmacies->sum('products_sum_price_stock_sold');

        // return $sales;

        $products = collect();

        // Loop through each pharmacy in the collection $sales->pharmacies
        foreach ($sales->pharmacies as $pharmacy) {
            // Calculate the total price for each product in the pharmacy
            $productsInPharmacy = $pharmacy->products->map(function ($product) {
                return $product;
            });

            // Merge products in the pharmacy with the existing "products" collection
            $products = $products->merge($productsInPharmacy);
        }

        // Group products by "product_id" and sum the stock and stock_sold for each group
        $products = $products->groupBy('product_id')->map(function ($groupedProducts) {
            return [
                'nama' => $groupedProducts->first()->product->nama,
                'stock' => $groupedProducts->sum('stock'),
                'stock_sold' => $groupedProducts->sum('stock_sold'),
                'price_stock' => $groupedProducts->sum('price_stock'),
                'price_stock_sold' => $groupedProducts->sum('price_stock_sold'),
            ];
        })->values();

        return view('admin.report.sales-report.detail', get_defined_vars());
    }
}
