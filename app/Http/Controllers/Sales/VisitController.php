<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\VisitReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VisitController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = VisitReport::query()
                ->when(isset(request()->timestamp), function ($q) {
                    $q->where('timestamp', request()->timestamp);
                })
                ->when(isset(request()->apotek), function ($q) {
                    $q->where('id_apotek', request()->apotek);
                })
                ->with('pharmacy')
                ->where('id_sales', Auth::user()->id_sales);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return " <a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->nama_sales' data-img='" . $item->image_url . "'>
                                <img src='" . $item->image_url . "' style='height: 100px; width: auto' /></a>";
                })
                ->rawColumns(['image_url'])
                ->toJson();
        }
        $pharmacies = Pharmacy::get();
        return view('sales.visit', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'id_apotek' => ['required'],
            'image' => ['required', 'image'],
        ]);

        $validatedData['images'] = $validatedData['image']->store('public');
        $validatedData['status'] = false;
        $validatedData['id_sales'] = Auth::user()->id_sales;

        $visit = VisitReport::create($validatedData);

        return response()->json(['message' => 'Laporan Berhasil Ditambahkan']);
    }
}
