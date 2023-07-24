<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PharmacyController extends Controller
{
    public function index()
    {

        $query = Pharmacy::with(['products'])->get();

        if (request()->wantsJson()) {
            $query = Pharmacy::query()
                ->with(['products.product'])
                ->when(isset(request()->prov), function ($q) {
                    $q->where('provinsi', request()->prov);
                })
                ->when(isset(request()->kota), function ($q) {
                    $q->where('kota', request()->kota);
                })
                ->when(isset(request()->product), function ($q) {
                    $q->whereHas('products', function ($p) {
                        $p->where('product_id', request()->product);
                    });
                })
                ->when(isset(request()->sales), function ($q) {
                    $q->where('id_sales', request()->sales);
                })
                ->with(['city', 'sales'])
                ->join('blw_kab', 'sales_apotek.kota', '=', 'blw_kab.id')
                ->orderBy('blw_kab.nama');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return " <button class='btn btn-warning btn-sm btn_edit' data-id='$item->id_apotek'><i class='ri-ball-pen-line'></i> Edit</button>
                            <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id_apotek'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->addColumn('product_string', function ($item) {
                    $string = '';
                    foreach ($item->products as $key => $product) {
                        $string .= $product->product->nama;
                        if ($key != count($item->products) - 1) $string .= ', ';
                    }

                    return $string;
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $provinces = Province::where('status', 1)->get();
        $cities = Regency::where('status', 1)->get();
        $products = Product::get();
        $saless = Sales::get();
        return view('admin.pharmacy', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'nama_apotek' => ['required'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'alamat' => ['required'],
            'product' => ['array', 'required'],
            'id_sales' => ['required'],
            'product.*' => ['required']
        ]);

        $products = '';

        $validatedData['produk'] = $products;
        $validatedData['kecamatan'] = '';
        $validatedData['keterangan'] = '';

        $pharmacy = Pharmacy::create($validatedData);

        foreach ($validatedData['product'] as $product) {
            PharmacyProduct::create([
                'product_id' => $product,
                'pharmacy_id' => $pharmacy->id_apotek
            ]);
        }

        return response()->json(['message' => 'Apotek Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $pharamcy = Pharmacy::with(['products'])->where('id_apotek', $id)->first();

        return response()->json($pharamcy);
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'nama_apotek' => ['required'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'alamat' => ['required'],
            'id_sales' => ['required'],
            'product.*' => ['required']
        ]);

        $products = $validatedData['product'];
        unset($validatedData['product']);

        $pharmacy = Pharmacy::where('id_apotek', $id)->first();
        $pharmacy->update($validatedData);

        $product_now = $pharmacy->products->pluck('product_id');
        $deleting_product = $product_now->diff($products);
        $inserting_product = collect($products)->diff($product_now);

        $pharmacy->products()->whereIn('product_id', $deleting_product)->delete();
        foreach ($inserting_product as $product) {
            $pharmacy->products()->create([
                'product_id' => $product
            ]);
        }

        return response()->json(['message' => 'Apotek Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        PharmacyProduct::where('pharmacy_id', $id)->delete();
        Pharmacy::where('id_apotek', $id)->delete();

        return response()->json(['message' => 'Apotek Berhasil Dihapus']);
    }

    public function export_pdf()
    {
        $data_sales = 'Semua';
        $data_provinsi = 'Semua';
        $data_kota = 'Semua';
        $data_product = 'Semua';
        $data = [];

        $pharmacy_data = Pharmacy::query()
            ->with([
                'sales',
                'city' => function ($query) {
                    $query->orderBy('nama');
                }
            ])
            ->where('id_sales', 'agung0')
            ->get();

        return $pharmacy_data;
    }
}
