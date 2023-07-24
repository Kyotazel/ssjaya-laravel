<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\DepositReport;
use App\Models\DepositReportPharmacyProduct;
use App\Models\PharmacyProduct;
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
                    $detail_route = route('admin.deposit-report.show', $item->id);
                    $edit_route = route('admin.deposit-report.edit', $item->id);
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                    <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                    <a class='dropdown-item' href='$edit_route'><i class='ri-ball-pen-line'></i> Edit</a>
                                    <button class='dropdown-item btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>
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
                $newPharmacy = $depositReport->pharmacies()->create([
                    'pharmacy_id' => $pharmacy['pharmacy_id']
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
                    PharmacyProduct::find($product["pharmacy_product_id"])->update(['stock_sold' => $product['stock']]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['message' => 'Status Berhasil Diperbarui']);
    }
}
