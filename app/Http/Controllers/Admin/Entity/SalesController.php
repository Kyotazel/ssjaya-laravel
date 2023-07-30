<?php

namespace App\Http\Controllers\Admin\Entity;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = Sales::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->toJson();
        }

        return view('admin.entity.sales');
    }

    public function store()
    {
        $validatedData = request()->validate([
            'nama' => ['required'],
            'id_sales' => ['required', 'unique:sales_user,id_sales'],
            'password' => ['required'],
        ]);

        $validatedData['password'] = md5($validatedData['password']);
        $validatedData['nomor'] = '';
        Sales::create($validatedData);

        return response()->json(['message' => 'Sales Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $sales = Sales::find($id);

        return response()->json($sales);
    }

    public function update($id)
    {
        $sales = Sales::find($id);
        $validatedData = request()->validate([
            'nama' => ['required'],
            'id_sales' => ['required'],
            'password' => ['nullable'],
        ]);

        if (request()->has('password')) {
            $validatedData['password'] = md5($validatedData['password']);
        }

        $sales->update($validatedData);

        return response()->json(['message' => 'Sales Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        $sales = Sales::find($id)->delete();

        return response()->json(['message' => 'Sales Berhasil Dihapus']);
    }
}
