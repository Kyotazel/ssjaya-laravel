<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\DepositReport;
use App\Models\DepositReportPharmacyProduct;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DepositReportController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = DepositReport::query()->with([
                'sales',
                'pharmacies.products'
            ])
                ->where('status', '!=', 'ARCHIVED')
                ->withCount(['pharmacies']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d M Y');
                })
                ->addColumn('product_sum', function ($item) {
                    return $item->pharmacies->sum(function ($pharmacy) {
                        return $pharmacy->products->sum('stock');
                    });
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.deposit-report.show', $item->id);
                    $edit_route = route('admin.deposit-report.edit', $item->id);

                    $archiveButton =  "<button class='dropdown-item archiveButton' data-id='$item->id'><i class='fa fa-trash'></i> Arsipkan</button>";
                    $editDropdown = ($item->status == 'PENDING' && authUser()->is_admin) ? "<a class='dropdown-item' href='$edit_route'><i class='ri-ball-pen-line'></i> Edit</a>" : '';
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                    $editDropdown
                                    $archiveButton
                                </div>
                            </div>";;
                })
                ->toJson();
        }

        $statuses = DepositReport::STATUS;
        return view('admin.report.deposit-report.index', get_defined_vars());
    }

    public function create()
    {
        $saless = Sales::get();
        return view('admin.report.deposit-report.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'sales_id' => ['required'],
            'pharmacies' => ['required']
        ]);

        try {
            DB::beginTransaction();

            $validatedData['code'] = '#0001';
            $depositReport = DepositReport::latest()->first();
            if ($depositReport) {
                $validatedData['code'] = '#' . sprintf('%04d', ($depositReport->id + 1));
            }
            $depositReport = DepositReport::create($validatedData);

            foreach ($validatedData['pharmacies'] as $pharmacy) {
                $imageUrl = '';
                if (isset($pharmacy['image'])) {
                    $imageUrl = $pharmacy['image']->store('public');
                }
                $newPharmacy = $depositReport->pharmacies()->create([
                    'pharmacy_id' => $pharmacy['pharmacy_id'],
                    'image_url' => $imageUrl
                ]);

                foreach ($pharmacy['products'] as $product) {
                    $newPharmacy->products()->create([
                        'pharmacy_product_id' => $product['product_id'],
                        'stock' => $product['stock'],
                        'price' => $product['price']
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json('ok');
    }

    public function edit($id)
    {
        $saless = Sales::get();
        $depositReport = DepositReport::with(['pharmacies.products'])->find($id);
        $thisSales = Sales::where('id', $depositReport->sales_id)->first();
        $pharmacies = Pharmacy::get();
        $products = Product::get();
        $pharmacyProduct = PharmacyProduct::with(['product'])->get();
        return view('admin.report.deposit-report.edit', get_defined_vars());
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'sales_id' => ['required'],
            'request_date' => ['nullable'],
            'pharmacies' => ['required']
        ]);

        try {
            DB::beginTransaction();

            $depositReport = DepositReport::with(['pharmacies.products'])->find($id);
            $pharmacies = $depositReport->pharmacies;
            foreach ($pharmacies as $pharmacy) {
                $pharmacy->products()->delete();
            }
            $depositReport->pharmacies()->delete();

            foreach ($validatedData['pharmacies'] as $pharmacy) {
                $imageUrl = '';
                if (isset($pharmacy['image_url'])) {
                    $imageUrl = $pharmacy['image']->store('public');
                }
                $newPharmacy = $depositReport->pharmacies()->create([
                    'pharmacy_id' => $pharmacy['pharmacy_id'],
                    'image_url' => $imageUrl
                ]);

                foreach ($pharmacy['products'] as $product) {
                    $newPharmacy->products()->create([
                        'pharmacy_product_id' => $product['product_id'],
                        'stock' => $product['stock']
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json('ok');
    }

    public function update_status(DepositReport $depositReport)
    {
        $validatedData = request()->validate([
            'status' => ['required']
        ]);

        try {
            DB::beginTransaction();

            $depositReport->update($validatedData);

            if ($validatedData['status'] == 'APPROVED') {
                $pharmacies = $depositReport->pharmacies;

                $new_products = [];
                foreach ($pharmacies as $pharmacy) {
                    $products = DepositReportPharmacyProduct::where('deposit_report_pharmacy_id', $pharmacy->id)->get();
                    foreach ($products as $product) {
                        $new_products[] = $product;
                    }
                }

                foreach ($new_products as $product) {
                    $pharmacyProduct = PharmacyProduct::find($product["pharmacy_product_id"]);
                    $pharmacyProduct->update([
                        'stock' => $pharmacyProduct->stock - $product['stock'],
                        'stock_sold' => $pharmacyProduct->stock_sold + $product['stock'],
                        'price_stock' =>  $pharmacyProduct->price_stock - $product['price'],
                        'price_stock_sold' => $pharmacyProduct->price_stock_sold + $product['price']
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['message' => 'Status Berhasil Diperbarui']);
    }

    public function show(DepositReport $depositReport)
    {
        $depositReport = DepositReport::with([
            'sales:id,nama',
            'pharmacies:id,deposit_report_id,pharmacy_id,image_url',
            'pharmacies.products:id,deposit_report_pharmacy_id,pharmacy_product_id,stock',
            'pharmacies.products.pharmacyProduct:id,product_id,pharmacy_id',
            'pharmacies.products.pharmacyProduct.product:id,nama,harga',
            'pharmacies.pharmacy:id_apotek,nama_apotek'
        ])
            ->withCount('pharmacies')
            ->find($depositReport->id);

        $depositReport->pharmacies = $depositReport->pharmacies->map(function ($pharmacy) {
            $pharmacy->product_sum = $pharmacy->products->sum(function ($product) {
                return $product->stock;
            });

            $pharmacy->products = $pharmacy->products->map(function ($product) {
                $product->total_price = $product->stock * $product->pharmacyProduct->product->harga;

                return $product;
            });

            $pharmacy->total_price = $pharmacy->products->sum('total_price');

            return $pharmacy;
        });

        $depositReport->total_price = $depositReport->pharmacies->sum('total_price');

        $depositReport->pharmacies_sum = $depositReport->pharmacies->sum(function ($pharmacy) {
            return $pharmacy->product_sum;
        });

        $productResumes = [];

        foreach ($depositReport->pharmacies as $pharmacy) {
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

        return view('admin.report.deposit-report.detail', get_defined_vars());
    }
}
