<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseAllExport;
use App\Exports\PurchaseMonthlyReport;
use App\Exports\PurchaseNotPaidExport;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseBill;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Purchase::query()
                ->with(['sales', 'pharmacy'])
                ->where('is_archived', false)
                ->when(request()->year, function ($q) {
                    $q->whereYear('date', request()->year);
                })
                ->when(request()->month, function ($q) {
                    $q->whereMonth('date', request()->month);
                })
                ->when(request()->day, function ($q) {
                    $q->whereDay('date', request()->day);
                })
                ->when(request()->sales_id, function ($q) {
                    $q->where('sales_id', request()->sales_id);
                })
                ->when(request()->status, function ($q) {
                    $q->where('status', request()->status)
                        ->orderByDesc('date')
                        ->whereBetween('date', [now()->subMonths(3), now()]);
                })
                ->when(request()->start_date && request()->end_date, function ($q) {
                    $q->whereBetween('date', [request()->start_date, request()->end_date]);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($item) {
                    return carbonParse($item->date)->format('d M Y');
                })
                ->editColumn('pharmacy.nama_apotek', function ($item) {
                    return $item->pharmacy->nama_apotek ?? '(Data Apotek Dihapus)';
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
                    $export_route = route('admin.purchase.export-detail', $item->id);

                    $archiveButton = (!$item->is_archived && authUser()->is_admin) ? "<button class='dropdown-item archiveButton' data-id='$item->id'><i class='fa fa-trash'></i> Arsipkan</button>" : '';
                    $editDropdown = ($item->status == Purchase::BELUMLUNAS && !$item->is_archived && authUser()->is_admin) ? "<a class='dropdown-item' href='$edit_route'><i class='ri-ball-pen-line'></i> Edit</a>" : '';
                    $payOffButton = ($item->status == Purchase::BELUMLUNAS) && !$item->is_archived  ? "<a class='dropdown-item payOffButton' data-id='$item->id' href='#'><i class='fa fa-check'></i> Lunasi</a>" : '';
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                $editDropdown
                                $payOffButton
                                $archiveButton
                                <a class='dropdown-item' target='_blank' href='$export_route'><i class='fas fa-file-pdf'></i> Export Pdf</a>
                                </div>
                            </div>";;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        $firstYear = now()->subYears(5)->year;
        $lastYear = now()->addYears(5)->year;
        $saless = Sales::get();
        return view('admin.purchase.index', get_defined_vars());
    }

    public function archived()
    {
        if (request()->wantsJson()) {
            $query = Purchase::query()
                ->with(['sales', 'pharmacy'])
                ->where('is_archived', true);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($item) {
                    if ($item->status == Purchase::LUNAS) {
                        return "<div class='badge badge-success'>LUNAS</div>";
                    }
                    return "<div class='badge badge-danger'>BELUM LUNAS</div>";
                })
                ->editColumn('pharmacy.nama_apotek', function ($item) {
                    return $item->pharmacy->nama_apotek ?? '(Data Apotek Dihapus)';
                })
                ->editColumn('date', function ($item) {
                    return carbonParse($item->date)->format('d M Y');
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.purchase.show', $item->id);
                    $edit_route = route('admin.purchase.edit', $item->id);

                    $archiveButton =  "<button class='dropdown-item archiveButton' data-id='$item->id'><i class='fa fa-cross'></i> Keluar Arsip</button>";
                    $editDropdown = ($item->status == Purchase::BELUMLUNAS && $item->is_archived == false && authUser()->is_admin) ? "<a class='dropdown-item' href='$edit_route'><i class='ri-ball-pen-line'></i> Edit</a>" : '';
                    $payOffButton = ($item->status == Purchase::BELUMLUNAS && $item->is_archived == false && authUser()->is_admin) ? "<a class='dropdown-item payOffButton' data-id='$item->id' href='#'><i class='fa fa-check'></i> Lunasi</a>" : '';
                    $payOffButton = ($item->status == Purchase::BELUMLUNAS && $item->is_archived == false && authUser()->is_admin) ? "<a class='dropdown-item payOffButton' data-id='$item->id' href='#'><i class='fa fa-check'></i> Lunasi</a>" : '';
                    $deleteButton = (authUser()->is_admin) ? "<a class='dropdown-item deleteButton' data-id='$item->id' href='#'><i class='fa fa-trash'></i> Hapus</a>" : '';
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                    $editDropdown
                                    $deleteButton
                                    $payOffButton
                                    $archiveButton
                                </div>
                            </div>";;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('admin.purchase.archived');
    }

    public function create()
    {
        $saless = Sales::get();
        $products = Product::get();
        return view('admin.purchase.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate(
            [
                'sales_id' => ['required'],
                'pharmacy_id' => ['required'],
                'yellow_image' => ['nullable', 'image'],
                'white_image' => ['nullable', 'image'],
                'products' => ['array'],
                'products.*.product_id' => ['required'],
                'products.*.stock' => ['required'],
                'code' => ['required', 'unique:purchases,code'],
                'date' => ['required']
            ],
            [
                'code.unique' => 'Kode Nota Sudah Digunakan'
            ]
        );

        if (request()->has('yellow_image')) {
            $validatedData['yellow_purchase'] = $validatedData['yellow_image']->store('public');
        }

        if (request()->has('white_image')) {
            $validatedData['white_purchase'] = $validatedData['white_image']->store('public');
        }

        try {
            DB::beginTransaction();

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

    public function edit($id)
    {
        $saless = Sales::get();
        $products = Product::get();
        $purchase = Purchase::with(['products', 'sales'])->find($id);
        $pharmacies = Pharmacy::where('id_sales', $purchase->sales->id_sales)->get();
        return view('admin.purchase.edit', get_defined_vars());
    }

    public function update($id)
    {
        $validatedData = request()->validate(
            [
                'sales_id' => ['required'],
                'pharmacy_id' => ['required'],
                'yellow_image' => ['nullable'],
                'white_image' => ['nullable'],
                'products' => ['array'],
                'products.*.product_id' => ['required'],
                'products.*.stock' => ['required'],
                'code' => ['required', 'unique:purchases,code,' . $id],
                'date' => ['required']
            ],
            [
                'code.unique' => 'Kode Nota Sudah Digunakan'
            ]
        );

        if (request()->has('yellow_image')) {
            $validatedData['yellow_purchase'] = $validatedData['yellow_image']->store('public');
        }

        if (request()->has('white_image')) {
            $validatedData['white_purchase'] = $validatedData['white_image']->store('public');
        }

        try {
            DB::beginTransaction();

            $purchase = Purchase::with(['products'])->find($id);

            $purchase->update($validatedData);

            $purchase->products()->delete();

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

    public function changeStatus($id)
    {
        $purchase = Purchase::find($id);

        $purchase->update(['status' => Purchase::LUNAS]);

        return response()->json();
    }

    public function archive($id)
    {
        $purchase = Purchase::find($id);

        if ($purchase->is_archived == true) {
            $purchase->update(['is_archived' => false]);
        } else {
            $purchase->update(['is_archived' => true]);
        }

        return response()->json();
    }

    public function show($id)
    {
        $purchase = Purchase::with(['products.product'])->find($id);
        return view('admin.purchase.detail', get_defined_vars());
    }

    public function uploadWhite($id)
    {
        $image = request()->image->store('public');

        $purchase = Purchase::find($id);
        $purchase->update(['white_purchase' => $image]);
        $newPurchase = Purchase::find($id);

        return response()->json(['img' => $newPurchase->white_purchase]);
    }


    public function uploadYellow($id)
    {
        $image = request()->image->store('public');

        $purchase = Purchase::find($id);
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
        return view('admin.purchase.bill', get_defined_vars());;
    }

    public function billCreate($id)
    {
        $saless = Sales::get();
        $products = Product::get();
        $purchase = Purchase::with(['products', 'sales'])->find($id);
        $pharmacies = Pharmacy::where('id_sales', $purchase->sales->id_sales)->get();

        return view('admin.purchase.create-bill', get_defined_vars());
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

    public function destroy($id)
    {
        $purchase = Purchase::find($id)->delete();

        return response()->json(['message' => 'Testimoni Berhasil Dihapus']);
    }

    private function listExportAll()
    {
        return Purchase::query()
            ->with(['sales', 'pharmacy.city'])
            ->where('is_archived', false)
            ->when(request()->year, function ($q) {
                $q->whereYear('date', request()->year);
            })
            ->when(request()->month, function ($q) {
                $q->whereMonth('date', request()->month);
            })
            ->when(request()->day, function ($q) {
                $q->whereDay('date', request()->day);
            })
            ->when(request()->sales_id, function ($q) {
                $q->where('sales_id', request()->sales_id);
            })
            ->get();
    }

    public function exportPdfList()
    {
        $purchases = $this->listExportAll();

        return view('pdf.purchase-report', get_defined_vars());

        $pdf = Pdf::loadView('pdf.purchase-report', get_defined_vars());
        return $pdf->download('Laporan Rekap Nota.pdf');
    }

    public function exportExcelList()
    {
        $purchases = $this->listExportAll();

        return Excel::download(new PurchaseAllExport($purchases), 'Laporan Rekap Nota.xlsx');
    }

    public function exportDetail($id)
    {
        $purchase = Purchase::with([
            'sales',
            'pharmacy.city',
            'products.product'
        ])->find($id);

        // return view('pdf.purchase-detail', get_defined_vars());

        $pdf = Pdf::loadView('pdf.purchase-detail', get_defined_vars());

        return $pdf->download("Detail Nota $purchase->code.pdf");
    }

    private function listExportNotPaid()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        return Purchase::query()
            ->with(['sales', 'pharmacy.city'])
            ->where('is_archived', false)
            ->where('status', Purchase::BELUMLUNAS)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('date')
            ->get();
    }

    public function exportPdfBelumLunas()
    {
        $purchases = $this->listExportNotPaid();

        $namedStart = carbonParse(request()->start_date)->format('d M Y');
        $namedEnd = carbonParse(request()->end_date)->format('d M Y');

        return view('pdf.purchase-report-belum-lunas', get_defined_vars());

        // $pdf = Pdf::loadView('pdf.purchase-report-belum-lunas', get_defined_vars());
        // return $pdf->download("Nota Belum Lunas ($namedStart = $namedEnd).pdf");
    }

    public function exportExcelBelumLunas()
    {
        $purchases = $this->listExportNotPaid();

        $namedStart = carbonParse(request()->start_date)->format('d M Y');
        $namedEnd = carbonParse(request()->end_date)->format('d M Y');

        return Excel::download(new PurchaseNotPaidExport($purchases), "Nota Belum Lunas ($namedStart - $namedEnd).xlsx");
    }

    public function rekapBulananView()
    {

        if (request()->wantsJson()) {
            $query = Purchase::query()
                ->with(['pharmacy.city'])
                ->withSum('products', 'stock')
                ->when(request()->year, function ($q) {
                    $q->whereYear('date', request()->year);
                })
                ->when(request()->start_month && request()->end_month, function ($q) {
                    $q->whereMonth('date', '>=', request()->start_month)
                        ->whereMonth('date', '<=', request()->end_month);
                })
                ->when(request()->filter_sales, function ($q) {
                    $q->where('sales_id', request()->filter_sales);
                });

            return DataTables::of($query)
                ->editColumn('pharmacy.nama_apotek', function ($item) {
                    return $item->pharmacy->nama_apotek ?? '(Data Apotek Dihapus)';
                })
                ->editColumn('pharmacy.city.nama', function ($item) {
                    return $item->pharmacy->city->nama ?? '(Data Apotek Dihapus)';
                })
                ->editColumn('date', function ($item) {
                    return carbonParse($item->date)->format('d M Y');
                })
                ->toJson();
        }

        $firstYear = now()->subYears(5)->year;
        $lastYear = now()->addYears(5)->year;
        $saless = Sales::get();
        return view('admin.purchase.rekap-bulanan', get_defined_vars());
    }

    public function exportExcelBulanan()
    {
        return Excel::download(new PurchaseMonthlyReport(request()->filter_sales, request()->year, request()->start_month, request()->end_month), 'Rekap Bulanan.xlsx');
    }
}
