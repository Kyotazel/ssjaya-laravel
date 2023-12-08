<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseBill;
use App\Models\Sales;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Purchase::query()
                ->with(['pharmacy'])
                ->where(['is_archived' => false, 'sales_id' => Auth::id()])
                ->when(request()->year, function ($q) {
                    $q->whereYear('date', request()->year);
                })
                ->when(request()->month, function ($q) {
                    $q->whereMonth('date', request()->month);
                })
                ->when(request()->day, function ($q) {
                    $q->whereDay('date', request()->day);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($item) {
                    return carbonParse($item->date)->format('d M Y');
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == Purchase::LUNAS) {
                        return "<div class='badge badge-success'>LUNAS</div>";
                    }
                    return "<div class='badge badge-danger'>BELUM LUNAS</div>";
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('sales.purchase.show', $item->id);
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                </div>
                            </div>";;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        $firstYear = now()->subYears(5)->year;
        $lastYear = now()->addYears(5)->year;
        return view('sales.purchase.index', get_defined_vars());
    }

    public function archived()
    {
        if (request()->wantsJson()) {
            $query = Purchase::query()
                ->with(['pharmacy'])
                ->where(['is_archived' => true, 'sales_id' => Auth::id()]);

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
                    $detail_route = route('sales.purchase.show', $item->id);
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                </div>
                            </div>";;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('sales.purchase.archived');
    }

    public function create()
    {
        $products = Product::get();
        $pharmacies = Pharmacy::where(['id_sales' => Auth::user()->id_sales])->get();
        return view('sales.purchase.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'pharmacy_id' => ['required'],
            'yellow_image' => ['nullable', 'image'],
            'products' => ['array'],
            'products.*.product_id' => ['required'],
            'products.*.stock' => ['required'],
        ]);

        $validatedData['sales_id'] = Auth::id();


        try {
            DB::beginTransaction();

            if (request()->has('yellow_image')) {
                $validatedData['yellow_purchase'] = $validatedData['yellow_image']->store('public');
            }

            $validatedData['code'] = '#0001';
            $purchase = Purchase::latest()->first();
            if ($purchase) {
                $validatedData['code'] = '#' . sprintf('%04d', ($purchase->id + 1));
            }

            $purchase = Purchase::create($validatedData);

            foreach ($validatedData['products'] as $product) {
                $product['price'] = 0;
                $purchase->products()->create($product);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }

    public function show($id)
    {
        $purchase = Purchase::with(['products.product'])->find($id);
        if ($purchase->sales_id != Auth::id()) {
            abort(403);
        }
        return view('sales.purchase.detail', get_defined_vars());
    }

    public function uploadWhite($id)
    {
        $image = request()->image->store('public');

        $purchase = Purchase::find($id);
        if ($purchase->sales_id != Auth::id()) {
            abort(403);
        }
        $purchase->update(['white_purchase' => $image]);
        $newPurchase = Purchase::find($id);

        return response()->json(['img' => $newPurchase->white_purchase]);
    }


    public function uploadYellow($id)
    {
        $image = request()->image->store('public');

        $purchase = Purchase::find($id);
        if ($purchase->sales_id != Auth::id()) {
            abort(403);
        }
        $purchase->update(['yellow_purchase' => $image]);
        $newPurchase = Purchase::find($id);

        return response()->json(['img' => $newPurchase->yellow_purchase]);
    }

    public function checkWhite($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase->white_purchase != null) {
            return response()->json(['image' => $purchase->white_purchase]);
        }

        return response('error', 500);
    }

    public function checkyellow($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase->yellow_purchase != null) {
            return response()->json(['image' => $purchase->yellow_purchase]);
        }

        return response('error', 500);
    }

    public function bill($id)
    {
        $purchase = Purchase::with([
            'products.product',
            'bills.products.product:id,nama'
        ])->find($id);
        if ($purchase->sales_id != Auth::id()) {
            abort(403);
        }
        return view('sales.purchase.bill', get_defined_vars());;
    }

    public function billCreate($id)
    {
        $saless = Sales::get();
        $products = Product::get();
        $purchase = Purchase::with(['products', 'sales'])->find($id);
        $pharmacies = Pharmacy::where('id_sales', $purchase->sales->id_sales)->get();

        if ($purchase->sales_id != Auth::id()) {
            abort(403);
        }
        return view('sales.purchase.create-bill', get_defined_vars());
    }

    public function billStore($id)
    {
        $validatedData = request()->validate([
            'image' => ['required', 'image'],
            'products' => ['array'],
            'products.*.product_id' => ['required'],
            'products.*.stock' => ['required'],
        ]);

        try {
            DB::beginTransaction();

            $validatedData['image_url'] = $validatedData['image']->store('public');

            $validatedData['code'] = '#TT0001';
            $existsBill = PurchaseBill::latest()->first();
            if ($existsBill) {
                $validatedData['code'] = '#TT' . sprintf('%04d', ($existsBill->id + 1));
            }

            $validatedData['purchase_id'] = $id;
            $bill = PurchaseBill::create($validatedData);

            foreach ($validatedData['products'] as $product) {
                $product['price'] = 0;
                $bill->products()->create($product);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }
}
