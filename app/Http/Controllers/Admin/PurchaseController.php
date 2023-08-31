<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sales;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Purchase::query()->with(['sales', 'pharmacy']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d M Y');
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == Purchase::LUNAS) {
                        return "<div class='badge badge-success'>LUNAS</div>";
                    }
                    return "<div class='badge badge-danger'>BELUM LUNAS</div>";
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.purchase.show', $item->id);
                    $edit_route = route('admin.purchase.edit', $item->id);

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
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('admin.purchase.index');
    }

    public function create()
    {
        $saless = Sales::get();
        $products = Product::get();
        return view('admin.purchase.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'sales_id' => ['required'],
            'pharmacy_id' => ['required'],
            'products' => ['array'],
            'products.*.product_id' => ['required'],
            'products.*.stock' => ['required'],
            'products.*.price' => ['required'],
        ]);

        $validatedData['code'] = '#0001';
        $purchase = Purchase::latest()->first();
        if ($purchase) {
            $validatedData['code'] = '#' . sprintf('%04d', ($purchase->id + 1));
        }

        try {
            DB::beginTransaction();

            $purchase = Purchase::create($validatedData);

            foreach ($validatedData['products'] as $product) {
                $purchase->products()->create($product);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }
}
