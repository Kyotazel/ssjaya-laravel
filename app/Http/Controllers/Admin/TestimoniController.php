<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TestimoniController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Testimoni::query()->with(['product']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->photo' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->editColumn('status', function ($item) {
                    if ($item->status) {
                        return "<button class='btn btn-sm btn-success change_to_not_active' data-id='$item->id'>Aktif</button>";
                    }

                    return "<button class='btn btn-sm btn-danger change_to_active' data-id='$item->id'>Tidak Aktif</button>";
                })
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->rawColumns(['image_url', 'status', 'action'])
                ->toJson();
        }
        $products = Product::get();
        return view('admin.testimoni', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'jabatan' => ['required'],
            'id_produk' => ['required'],
            'komentar' => ['required'],
            'image' => ['required'],
        ]);

        $validatedData['foto'] = $validatedData['image']->store('public');
        $validatedData['status'] = 1;

        $testimoni = Testimoni::create($validatedData);

        return response()->json(['message' => 'Testimoni Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $testimoni = Testimoni::where('id', $id)->first();

        return response()->json($testimoni);
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'jabatan' => ['required'],
            'id_produk' => ['required'],
            'komentar' => ['required'],
            'image' => ['nullable'],
        ]);

        if (request()->has('image')) {
            $validatedData['foto'] = $validatedData['image']->store('public');
        }

        $testimoni = Testimoni::where('id', $id)->first();
        $testimoni->update($validatedData);

        return response()->json(['message' => 'Testimoni Berhasil Diperbarui']);
    }

    public function status($id)
    {
        $testimoni = Testimoni::find($id);

        $testimoni->update(['status' => request()->status]);

        return response()->json(['message' => 'Status Testimoni Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        Testimoni::where('id', $id)->delete();

        return response()->json(['message' => 'Testimoni Berhasil Dihapus']);
    }
}
