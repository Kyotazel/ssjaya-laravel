<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Upload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Product::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->image_url' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->editColumn('merk_image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->merk_image_url' data-img='$item->merk_image_url'><img src='$item->merk_image_url' style='height: 100px; width: auto' /></a>";
                })
                ->editColumn(('harga'), function ($item) {
                    return "Rp. " . number_format($item->harga);
                })
                ->addColumn('comp_and_cert', function ($item) {
                    $cert_route = route('admin.certification.index', ['product' => $item]);
                    $comp_route = route('admin.composition.index', ['product' => $item]);
                    return "<a class='btn btn-primary text-light mb-1' href='$comp_route'>Komposisi</a><br><a class='btn btn-primary text-light' href='$cert_route'>Sertifikasi</a>";
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.product.show', $item->id);
                    $edit_route = route('admin.product.edit', $item->id);
                    return "<div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    Action 
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                    <a class='dropdown-item' href='$detail_route'><i class='fa fa-desktop'></i> Detail</a>
                                    <a class='dropdown-item' href='$edit_route'><i class='ri-ball-pen-line'></i> Edit</a>
                                    <button class='dropdown-item btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>
                                </div>
                            </div>";
                })
                ->rawColumns(['nama', 'image_url', 'merk_image_url', 'comp_and_cert', 'action'])
                ->toJson();
        }

        return view('admin.product.index');
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function store()
    {

        $validatedData = request()->validate([
            'nama' => ['required'],
            'harga' => ['required'],
            'deskripsi' => ['required'],
            'aturan' => ['required'],
            'manfaat' => ['required'],
            'merk' => ['required'],
            'image' => ['required'],
        ]);

        $validatedData['url'] = str_replace(' ', '-', $validatedData['nama']);
        $validatedData['merk_photo'] = $validatedData['merk']->store('public');
        $photo = $validatedData['image']->store('public');

        try {
            DB::beginTransaction();
            $product = Product::create($validatedData);

            Upload::create([
                'idproduk' => $product->id,
                'jenis' => 1,
                'nama' => $photo,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.product.index');
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->with(['compositions', 'certificates'])->first();
        return view('admin.product.detail', get_defined_vars());
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        return view('admin.product.edit', get_defined_vars());
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'harga' => ['required'],
            'deskripsi' => ['required'],
            'aturan' => ['required'],
            'manfaat' => ['required'],
            'merk' => ['nullable'],
            'image' => ['nullable'],
        ]);

        $validatedData['url'] = str_replace(' ', '-', $validatedData['nama']);

        if (request()->has('merk')) {
            $validatedData['merk_photo'] = $validatedData['merk']->store('public');
        }
        unset($validatedData['merk']);

        if (request()->has('image')) {
            $photo = $validatedData['image']->store('public');

            Upload::where('idproduk', $id)->update([
                'nama' => $photo
            ]);
        }
        unset($validatedData['image']);

        Product::where('id', $id)->update($validatedData);

        return redirect()->route('admin.product.index');
    }

    public function destroy($id)
    {
        Upload::where('idproduk', $id)->delete();
        Product::where('id', $id)->delete();

        return response()->json(['message' => 'Produk Berhasil Dihapus']);
    }
}
