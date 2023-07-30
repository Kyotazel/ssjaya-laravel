<?php

namespace App\Http\Controllers\Sales\Report;

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
            $query = DepositReport::query()->with(['sales', 'pharmacies.products'])->withCount(['pharmacies']);

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
                    $detail_route = route('sales.deposit-report.show', $item->id);

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

        $statuses = DepositReport::STATUS;
        return view('sales.report.deposit-report.index', get_defined_vars());
    }

    public function create()
    {
        $sales = Sales::where('id_sales', authUser()->id_sales)->first();
        return view('sales.report.deposit-report.create', get_defined_vars());
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

        return view('sales.report.deposit-report.detail', get_defined_vars());
    }
}
