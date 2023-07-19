<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = BlogCategory::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('color', function ($item) {
                    return "<p class='category_article' style='background-color: $item->color'>$item->color</p>";
                })
                ->addColumn('action', function ($item) {
                    return "<button class='btn btn-warning btn-sm btn_edit' data-id='$item->id'><i class='ri-ball-pen-line'></i> Edit</button>
                    <button class='btn btn-danger btn-sm btn_delete' data-id='$item->id'><i class='ri-delete-bin-line'></i> Hapus</button>";
                })
                ->rawColumns(['color', 'action'])
                ->toJson();
        }
        return view('admin.blog-category');
    }

    public function store()
    {
        $validatedData = request()->validate([
            'name' => ['required'],
            'color' => ['required'],
        ]);

        $category = BlogCategory::create($validatedData);

        return response()->json(['message' => 'Kategori Blog Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $category = BlogCategory::where('id', $id)->first();

        return response()->json($category);
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'name' => ['required'],
            'color' => ['required'],
        ]);

        $category = BlogCategory::where('id', $id)->first();
        $category->update($validatedData);

        return response()->json(['message' => 'Kategori Blog Berhasil Diperbarui']);
    }

    public function destroy($id)
    {
        $category = BlogCategory::where('id', $id)->delete();

        return response()->json(['message' => 'Kategori Blog Berhasil Dihapus']);
    }
}
