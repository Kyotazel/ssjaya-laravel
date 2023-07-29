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

    public function show($id)
    {
        $sales = Sales::where('id', $id)
            ->with([
                'pharmacies:id_apotek,id_sales',
                'pharmacies.products:pharmacy_id,product_id,stock,stock_sold',
                'pharmacies.products.product:id,nama,harga'
            ])
            ->withCount('pharmacies')
            ->first();

        $sales->pharmacies = $sales->pharmacies->map(function ($pharmacy) {
            $pharmacy->products = $pharmacy->products->map(function ($product) {
                $product->price_stock = $product->stock * $product->product->harga;
                $product->price_stock_sold = $product->stock_sold * $product->product->harga;

                return $product;
            });

            $pharmacy->products_sum_stock = $pharmacy->products->sum('stock');
            $pharmacy->products_sum_stock_sold = $pharmacy->products->sum('stock_sold');
            $pharmacy->products_price_stock = $pharmacy->products->sum(function ($product) {
                return $product->stock * $product->product->harga;
            });
            $pharmacy->products_price_stock_sold = $pharmacy->products->sum(function ($product) {
                return $product->stock_sold * $product->product->harga;
            });

            return $pharmacy;
        });

        $sales->products_sum_stock = $sales->pharmacies->sum('products_sum_stock');
        $sales->products_sum_stock_sold = $sales->pharmacies->sum('products_sum_stock_sold');
        $sales->products_price_stock = $sales->pharmacies->sum('products_price_stock');
        $sales->products_price_stock_sold = $sales->pharmacies->sum('products_price_stock_sold');

        // return $sales;

        $products = collect();

        // Loop through each pharmacy in the collection $sales->pharmacies
        foreach ($sales->pharmacies as $pharmacy) {
            // Calculate the total price for each product in the pharmacy
            $productsInPharmacy = $pharmacy->products->map(function ($product) {
                $product->price_stock = $product->stock * $product->product->harga;
                $product->price_stock_sold = $product->stock_sold * $product->product->harga;
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
