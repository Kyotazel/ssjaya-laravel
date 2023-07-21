<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $query = Blog::query()->with(['product', 'category'])->orderBy('id', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_url', function ($item) {
                    return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$item->image_url' data-img='$item->image_url'><img src='$item->image_url' style='height: 100px; width: auto' /></a>";
                })
                ->addColumn('product_custom', function ($item) {
                    if ($item->product) {
                        $product = $item->product;
                        return "<a href='#' data-toggle='modal' data-target='#imageModal' data-title='$product->nama' data-img='$product->image_url' class='btn btn-success'>$product->nama</a>";
                    }

                    return "";
                })
                ->addColumn('category_custom', function ($item) {
                    if ($item->category) {
                        $category = $item->category;
                        return "<p class='category_article' style='background-color: $category->color'>$category->name</p>";
                    }

                    return "";
                })
                ->addColumn('action', function ($item) {
                    $detail_route = route('admin.blog.show', $item->id);
                    $edit_route = route('admin.blog.edit', $item->id);
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
                ->rawColumns(['image_url', 'product_custom', 'category_custom', 'action'])
                ->toJson();
        }

        return view('admin.blog.index');
    }

    public function create()
    {
        $categories = BlogCategory::get();
        $products = Product::get();
        return view('admin.blog.create', get_defined_vars());
    }

    public function store()
    {
        $validatedData = request()->validate([
            'judul' => ['required'],
            'id_category' => ['required', 'exists:blw_blog_category,id'],
            'id_produk' => ['required', 'exists:blw_produk,id'],
            'image' => ['required', 'image'],
            'konten' => ['required']
        ]);

        $validatedData['img'] = $validatedData['image']->store('public');
        $validatedData['url'] = str_replace(' ', '-', $validatedData['judul']);
        $validatedData['views'] = 0;

        $blog = Blog::create($validatedData);

        return redirect()->route('admin.blog.index');
    }

    public function show($id)
    {
        $blog = Blog::where('id', $id)->first();
        return view('admin.blog.detail', get_defined_vars());
    }

    public function edit($id)
    {
        $blog = Blog::where('id', $id)->first();
        $categories = BlogCategory::get();
        $products = Product::get();
        return view('admin.blog.edit', get_defined_vars());
    }

    public function update($id)
    {
        $validatedData = request()->validate([
            'judul' => ['required'],
            'id_category' => ['required', 'exists:blw_blog_category,id'],
            'id_produk' => ['required', 'exists:blw_produk,id'],
            'image' => ['nullable', 'image'],
            'konten' => ['required']
        ]);

        if (request()->has('image')) {
            $validatedData['img'] = $validatedData['image']->store('public');
        }

        $blog = Blog::where('id', $id)->first();
        $blog->update($validatedData);

        return redirect()->route('admin.blog.index');
    }

    public function destroy($id)
    {
        Blog::where('id', $id)->delete();

        return response()->json(['message' => 'Blog Berhasil Dihapus']);
    }
}
