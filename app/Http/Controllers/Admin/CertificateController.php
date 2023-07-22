<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CertificateController extends Controller
{
    public function index(Product $product)
    {
        if (request()->wantsJson()) {
            $query = Certification::where('id_produk', $product->id);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->nama' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->rawColumns(['image_url', 'action'])
                ->toJson();
        }
        return view('admin.product.certificate.index', get_defined_vars());
    }

    public function store(Product $product)
    {
        $validatedData = request()->validate([
            'image' => ['required', 'image']
        ]);

        $validatedData['id_produk'] = $product->id;

        $validatedData['image'] = $validatedData['image']->store('public');

        Certification::create($validatedData);

        return response()->json(['message' => 'Sertifikasi Berhasil Ditambahkan']);
    }

    public function update(Product $product, Certification $certification)
    {
        $validatedData = request()->validate([
            'image' => ['required', 'image']
        ]);

        if (request()->has('image')) {
            $validatedData['image'] = $validatedData['image']->store('public');
        }

        $certification->update($validatedData);

        return response()->json(['message' => 'Sertifikasi Berhasil Diperbarui']);
    }

    public function destroy(Product $product, Certification $certification)
    {
        $certification->delete();

        return response()->json(['message' => 'Sertifikasi Berhasil Dihapus']);
    }
}
