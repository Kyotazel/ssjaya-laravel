<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Carousel;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Testimoni;
use App\Models\Upload;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::get();
        $products = Product::get();
        $testimonials = Testimoni::with('product')->get();
        $blogs = Blog::limit(3)
            ->with(['category'])
            ->orderBy('tgl', 'DESC')
            ->get();
        $provinces = Province::get();

        return view('landing.home', get_defined_vars());
    }

    public function aboutUs()
    {
        return view('landing.about_us');
    }

    public function consultation()
    {
        return view('landing.consultation');
    }

    public function product()
    {
        $products = Product::get();
        return view('landing.product', get_defined_vars());
    }

    public function productDetail($product)
    {
        $product = Product::where('url', $product)
            ->with(['certificates', 'compositions'])
            ->first();

        return view('landing.product_detail', get_defined_vars());
    }

    public function article()
    {
        $articles = Blog::with(['category'])
            ->orderBy('tgl', 'DESC')
            ->get();

        return view('landing.article', get_defined_vars());
    }

    public function articleDetail($url)
    {
        $article = Blog::where('url', $url)
            ->with(['category'])
            ->first();

        return view('landing.article_detail', get_defined_vars());
    }

    public function listMitra($name)
    {
        $name = str_replace('-', ' ', $name);

        $province = Province::where('nama', $name)->first();
        $cities = Regency::where(['idprov' => $province->id, 'status' => 1])->get();

        if (!$province) {
            abort(404);
        }

        return view('landing.list_mitra', get_defined_vars());
    }

    public function listApotek()
    {
        $pharmacies = Pharmacy::query()
            ->where([
                'provinsi' => request()->prov,
                'kota' => request()->city,
            ])
            ->when(request()->has('apotek'), function ($query) {
                $query->where('nama_apotek', 'LIKE', '%' . request()->apotek . '%');
            })
            ->with(['sales'])
            ->get();

        $regency = Regency::find(request()->city);

        $html = '';
        if ($pharmacies) {
            $html .= '<ul class="splide__list">';
            foreach ($pharmacies as $pharmacy) {
                $html .=
                    "<li class='splide__slide'>
                    <div class='mitra_list d-flex flex-column'>
                        <div class='main d-flex flex-column justify-content-between'>
                            <h4 class='main_title'>" .
                    strtoupper($pharmacy->nama_apotek) .
                    "</h4>
                            <p style='text-align: center; font-style: italic; font-size: 18px'>$pharmacy->alamat.</p><br>
                            <p style='text-align: left; font-size: 16px; font-style: italic; color: black'><b>Produk</b> : </p>
                            <p style='text-align: left; font-size: 15px: font-style: italic'>$pharmacy->produk</p>
                        </div>
                    </div>
                </li>";
            }
            $html .= '</ul>';
            return response()->json(['status' => true, 'html_mitra' => $html, 'city' => "$regency->nama"]);
        } else {
            $html = "<h2 style='text-align: center'>Data Tidak Ditemukan</h2>";
            return response()->json(['html_mitra' => $html]);
        }
    }
}
