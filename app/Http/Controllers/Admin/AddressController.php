<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AddressController extends Controller
{
    public function province_index()
    {
        if (request()->wantsJson()) {
            $query = Province::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($item) {
                    if ($item->status) {
                        return "<button class='btn btn-sm btn-success change_to_not_active' data-id='$item->id'>Aktif</button>";
                    }

                    return "<button class='btn btn-sm btn-danger change_to_active' data-id='$item->id'>Tidak Aktif</button>";
                })
                ->rawColumns(['status'])
                ->toJson();
        }
        return view('admin.province');
    }

    public function province_status($id)
    {
        $province = Province::where('id', $id)->first();

        $province->update(['status' => request()->status]);

        return response()->json(['message' => 'Provinsi Berhasil Diperbarui']);
    }

    public function city_index()
    {
        if (request()->wantsJson()) {
            $query = Regency::query()->with(['province']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($item) {
                    if ($item->status) {
                        return "<button class='btn btn-sm btn-success change_to_not_active' data-id='$item->id'>Aktif</button>";
                    }

                    return "<button class='btn btn-sm btn-danger change_to_active' data-id='$item->id'>Tidak Aktif</button>";
                })
                ->rawColumns(['status'])
                ->toJson();
        }
        return view('admin.city');
    }

    public function city_status($id)
    {
        $city = Regency::where('id', $id)->first();

        $city->update(['status' => request()->status]);

        return response()->json(['message' => 'Kota Berhasil Diperbarui']);
    }
}
