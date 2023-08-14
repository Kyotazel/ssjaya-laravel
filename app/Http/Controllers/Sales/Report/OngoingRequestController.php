<?php

namespace App\Http\Controllers\Sales\Report;

use App\Http\Controllers\Controller;
use App\Models\OngoingRequest;
use App\Models\OngoingRequestPharmacyProduct;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\Sales;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OngoingRequestController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = OngoingRequest::query()->with([
                'pharmacies.products'
            ])
                ->where('sales_id', authUser()->id)
                ->where('status', '!=', 'ARCHIVED')
                ->withCount(['pharmacies']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d M Y');
                })
                ->editColumn('request_date', function ($item) {
                    return Carbon::parse($item->request_date)->format('d M Y');
                })
                ->addColumn('product_sum', function ($item) {
                    return $item->pharmacies->sum(function ($pharmacy) {
                        return $pharmacy->products->sum('stock');
                    });
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('sales.ongoing-request.show', $item->id);

                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                </div>
                            </div>";;
                })
                ->toJson();
        }

        $statuses = OngoingRequest::STATUS;
        return view('sales.report.ongoing-request.index', get_defined_vars());
    }

    public function create()
    {
        $products = Product::get();
        $sales = Sales::where('id_sales', authUser()->id_sales)->first();
        return view('sales.report.ongoing-request.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'sales_id' => ['required'],
            'request_date' => ['nullable'],
            'pharmacies' => ['required']
        ]);

        if ($validatedData['request_date'] == null) {
            $validatedData['request_date'] = now();
        }

        try {
            DB::beginTransaction();

            $validatedData['code'] = '#0001';
            $ongoingRequest = OngoingRequest::latest()->first();
            if ($ongoingRequest) {
                $validatedData['code'] = '#' . sprintf('%04d', ($ongoingRequest->id + 1));
            }
            $ongoingRequest = OngoingRequest::create($validatedData);

            foreach ($validatedData['pharmacies'] as $pharmacy) {
                $newPharmacy = $ongoingRequest->pharmacies()->create([
                    'pharmacy_id' => $pharmacy['pharmacy_id']
                ]);

                foreach ($pharmacy['products'] as $product) {
                    $pharmacyProduct = PharmacyProduct::where([
                        'pharmacy_id' => $pharmacy['pharmacy_id'],
                        'product_id' => $product['product_id']
                    ])
                        ->first();
                    if (!$pharmacyProduct) {
                        $pharmacyProduct = PharmacyProduct::create([
                            'pharmacy_id' => $pharmacy['pharmacy_id'],
                            'product_id' => $product['product_id'],
                            'stock' => 0,
                            'stock_sold' => 0
                        ]);
                    }
                    $thisProduct = Product::where('id', $product['product_id'])->first();
                    $newPharmacy->products()->create([
                        'pharmacy_product_id' => $pharmacyProduct->id,
                        'stock' => $product['stock'],
                        'price' => $thisProduct->harga
                    ]);

                    // $newPharmacy->products()->create([
                    //     'pharmacy_product_id' => $pharmacyProduct->id,
                    //     'stock' => $product['stock'],
                    //     'price' => $product['price']
                    // ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json('ok');
    }

    public function show(OngoingRequest $ongoingRequest)
    {
        $ongoingRequest = OngoingRequest::with([
            'sales:id,nama',
            'pharmacies:id,ongoing_request_id,pharmacy_id',
            'pharmacies.products:id,ongoing_request_pharmacy_id,pharmacy_product_id,stock,price',
            'pharmacies.products.pharmacyProduct:id,product_id,pharmacy_id',
            'pharmacies.products.pharmacyProduct.product:id,nama,harga',
            'pharmacies.pharmacy:id_apotek,nama_apotek'
        ])
            ->withCount('pharmacies')
            ->find($ongoingRequest->id);

        $ongoingRequest->pharmacies = $ongoingRequest->pharmacies->map(function ($pharmacy) {
            $pharmacy->product_sum = $pharmacy->products->sum(function ($product) {
                return $product->stock;
            });

            $pharmacy->products = $pharmacy->products->map(function ($product) {
                $product->total_price = $product->stock * $product->price;

                return $product;
            });

            $pharmacy->total_price = $pharmacy->products->sum('total_price');

            return $pharmacy;
        });

        $ongoingRequest->total_price = $ongoingRequest->pharmacies->sum('total_price');

        $ongoingRequest->pharmacies_sum = $ongoingRequest->pharmacies->sum(function ($pharmacy) {
            return $pharmacy->product_sum;
        });

        $productResumes = [];

        foreach ($ongoingRequest->pharmacies as $pharmacy) {
            foreach ($pharmacy['products'] as $product) {
                $existingProductKey = collect($productResumes)->search(function ($item) use ($product) {
                    return $item['product_id'] === $product['pharmacyProduct']['product_id'];
                });

                if ($existingProductKey !== false) {
                    $productResumes[$existingProductKey]['stock'] += $product['stock'];
                } else {
                    $productResumes[] = [
                        'product_id' => $product['pharmacyProduct']['product_id'],
                        'product_name' => $product['pharmacyProduct']['product']['nama'],
                        'stock' => $product['stock'],
                    ];
                }
            }
        }

        return view('sales.report.ongoing-request.detail', get_defined_vars());
    }
}
