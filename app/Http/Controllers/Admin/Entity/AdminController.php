<?php

namespace App\Http\Controllers\Admin\Entity;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $query = Admin::query()->where('is_admin', 0);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->toJson();
        }

        return view('admin.entity.admin');
    }

    public function store()
    {
        $validatedData = request()->validate([
            'name' => ['required'],
            'username' => ['required', 'unique:app_user,username'],
            'password' => ['required'],
        ]);

        $validatedData['password'] = hash('sha512', $validatedData['password'] . '@octacore');

        $validatedData['is_admin'] = false;
        $validatedData['status'] = true;
        $validatedData['is_delete'] = false;
        $validatedData['last_login'] = date('Y-m-d H:i:s');

        Admin::create($validatedData);

        return response()->json(['message' => 'Admin Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $admin = Admin::find($id);

        return response()->json($admin);
    }

    public function update($id)
    {
        $admin = Admin::find($id);
        $validatedData = request()->validate([
            'name' => ['required'],
            'username' => ['required'],
            'password' => ['nullable'],
        ]);

        if (request()->has('password')) {
            $validatedData['password'] = hash('sha512', $validatedData['password'] . '@octacore');
        }

        $admin->update($validatedData);

        return response()->json(['message' => 'Admin Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        $admin = Admin::find($id)->delete();

        return response()->json(['message' => 'Admin Berhasil Dihapus']);
    }
}
