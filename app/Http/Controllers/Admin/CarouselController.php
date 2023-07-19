<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CarouselController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Carousel::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->photo' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->addColumn('action', function ($item) {
                    return " <button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                            <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->editColumn('status', function ($item) {
                    if ($item->status) {
                        return "<button class='btn btn-sm btn-success change_to_not_active' data-id='$item->id'>Aktif</button>";
                    }

                    return "<button class='btn btn-sm btn-danger change_to_active' data-id='$item->id'>Tidak Aktif</button>";
                })
                ->rawColumns(['image_url', 'action', 'status'])
                ->toJson();
        }

        return view('admin.carousel', get_defined_vars());
    }

    public function status($id)
    {
        $carousel = Carousel::find($id);

        $carousel->update(['status' => request()->status]);

        return response()->json(['message' => 'Status Berhasil Diperbarui']);
    }

    public function store()
    {
        $validatedData = request()->validate([
            'image' => ['required', 'image']
        ]);

        $validatedData['status'] = 1;

        $validatedData['photo'] = $validatedData['image']->store('public');

        $carousel = Carousel::create($validatedData);

        return response()->json(['message' => 'Carousel Berhasil Ditambahkan']);
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'image' => ['required', 'image']
        ]);

        $validatedData['photo'] = $validatedData['image']->store('public');
        $carousel = Carousel::find($id);
        $carousel->update($validatedData);

        return response()->json(['message' => 'Carousel Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        Carousel::where('id', $id)->delete();

        return response()->json(['message' => 'Carousel Berhasil Dihapus']);
    }
}
