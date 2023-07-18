<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PharmacyController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Pharmacy::query()
                ->when(isset(request()->prov), function ($q) {
                    $q->where('provinsi', request()->prov);
                })
                ->when(isset(request()->kota), function ($q) {
                    $q->where('kota', request()->kota);
                })
                ->when(isset(request()->product), function ($q) {
                    $q->where('produk', 'LIKE', '%' . request()->product . '%');
                })
                ->with('city')
                ->join('blw_kab', 'sales_apotek.kota', '=', 'blw_kab.id')
                ->where('id_sales', Auth::user()->id_sales)
                ->orderBy('blw_kab.nama');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return " <button class='btn btn-warning btn-sm btn_edit' data-id='$item->id_apotek'><i class='ri-ball-pen-line'></i> Edit</button>
                            <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id_apotek'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $provinces = Province::where('status', 1)->get();
        $cities = Regency::where('status', 1)->get();
        $products = Product::get();
        return view('sales.pharmacy', get_defined_vars());
    }

    public function store()
    {

        $validatedData = request()->validate([
            'nama_apotek' => ['required'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'alamat' => ['required'],
            'product' => ['array', 'required'],
            'product.*' => ['required']
        ]);


        $count =  count($validatedData['product']);
        $products = '';
        foreach ($validatedData['product'] as $key => $product) {
            if ($count - 1 != $key) {
                $products .= $product . ", ";
            } else {
                $products .= $product;
            }
        }

        $validatedData['produk'] = $products;
        $validatedData['id_sales'] = Auth::user()->id_sales;
        $validatedData['kecamatan'] = '';
        $validatedData['keterangan'] = '';

        $pharmacy = Pharmacy::create($validatedData);

        return response()->json(['message' => 'Apotek Berhasil Ditambahkan']);
    }

    public function destroy($id)
    {
        Pharmacy::where('id_apotek', $id)->delete();

        return response()->json(['message' => 'Apotek Berhasil Dihapus']);
    }

    public function edit($id)
    {
        $pharamcy = Pharmacy::where('id_apotek', $id)->first();

        return response()->json(['status' => true, 'data' => $pharamcy]);
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'nama_apotek' => ['required'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'alamat' => ['required'],
        ]);

        $count =  count(request()->product);
        $products = '';
        foreach (request()->product as $key => $product) {
            if ($count - 1 != $key) {
                $products .= $product . ", ";
            } else {
                $products .= $product;
            }
        }

        $validatedData['produk'] = $products;

        $pharmacy = Pharmacy::where('id_apotek', $id)->update($validatedData);

        return response()->json(['message' => 'Apotek Berhasil Diperbarui']);
    }
}
