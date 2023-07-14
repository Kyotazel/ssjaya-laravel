<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Carousel;
use App\Models\Product;
use App\Models\Province;
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
        $blogs = Blog::get();
        $provinces = Province::get();

        return view('landing.home', get_defined_vars());
    }
}
