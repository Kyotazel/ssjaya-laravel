<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Models\VisitReport;
use Illuminate\Http\Request;
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
                ->when(isset(request()->sales), function ($q) {
                    $q->where('id_sales', request()->sales);
                })
                ->with(['pharmacy', 'sales']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->image_url' 
                    data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->editColumn('timestamps', function ($item) {
                    return date('d M Y', strtotime($item->timestamp));
                })
                ->editColumn('status', function ($item) {
                    if ($item->status) {
                        return "<button class='btn btn-sm btn-success change_to_not_active' data-id='$item->id_laporan'>Dikonfirmasi</button>";
                    }
                    return "<button class='btn btn-sm btn-danger change_to_active' data-id='$item->id_laporan'>Belum Dikonfirmasi</button>";
                })
                ->rawColumns(['image_url', 'status'])
                ->toJson();
        }
        $saless = Sales::get();
        return view('admin.visit', get_defined_vars());
    }

    public function status($id)
    {
        $visit = VisitReport::where('id_laporan', $id);

        $visit->update(['status' => request()->status]);

        return response()->json(['message' => 'Status Berhasil Diperbarui']);
    }
}
