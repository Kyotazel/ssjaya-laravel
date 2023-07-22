<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CompositionController extends Controller
{
    public function index(Product $product)
    {
        if (request()->wantsJson()) {
            $query = Composition::where('id_produk', $product->id);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->nama' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->rawColumns(['nama', 'image_url', 'action'])
                ->toJson();
        }
        return view('admin.product.composition.index', get_defined_vars());
    }

    public function store(Product $product)
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'image' => ['required', 'image']
        ]);

        $validatedData['id_produk'] = $product->id;

        $validatedData['image'] = $validatedData['image']->store('public');

        Composition::create($validatedData);

        return response()->json(['message' => 'Komposisi Berhasil Ditambahkan']);
    }

    public function edit(Product $product, Composition $composition)
    {
        return response()->json($composition->only('nama'));
    }

    public function update(Product $product, Composition $composition)
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'image' => ['nullable', 'image']
        ]);
        unset($validatedData['image']);

        if (request()->has('image')) {
            $validatedData['image'] = request()->image->store('public');
        }

        $composition->update($validatedData);

        return response()->json(['message' => 'Komposisi Berhasil Diperbarui']);
    }

    public function destroy(Product $product, Composition $composition)
    {
        $composition->delete();

        return response()->json(['message' => 'Komposisi Berhasil Dihapus']);
    }
}
