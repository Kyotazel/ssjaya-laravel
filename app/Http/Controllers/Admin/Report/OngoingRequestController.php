<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\OngoingRequest;
use App\Models\OngoingRequestPharmacyProduct;
use App\Models\PharmacyProduct;
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
            $query = OngoingRequest::query()->with(['sales', 'pharmacies.products'])->withCount(['pharmacies']);

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
                    $detail_route = route('admin.ongoing-request.show', $item->id);
                    $edit_route = route('admin.ongoing-request.edit', $item->id);
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

        $statuses = OngoingRequest::STATUS;
        return view('admin.report.ongoing-request.index', get_defined_vars());
    }

    public function create()
    {
        $saless = Sales::get();
        return view('admin.report.ongoing-request.create', get_defined_vars());
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

    public function update_status(OngoingRequest $ongoingRequest)
    {
        $validatedData = request()->validate([
            'status' => ['required']
        ]);

        try {
            DB::beginTransaction();

            $ongoingRequest->update($validatedData);

            if ($validatedData['status'] == 'APPROVED') {
                $pharmacies = $ongoingRequest->pharmacies;

                $new_products = [];
                foreach ($pharmacies as $pharmacy) {
                    $products = OngoingRequestPharmacyProduct::where('ongoing_request_pharmacy_id', $pharmacy->id)->get();
                    foreach ($products as $product) {
                        $new_products[] = $product;
                    }
                }

                foreach ($new_products as $product) {
                    PharmacyProduct::find($product["pharmacy_product_id"])->update(['stock' => $product['stock']]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['message' => 'Status Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
    }
}
