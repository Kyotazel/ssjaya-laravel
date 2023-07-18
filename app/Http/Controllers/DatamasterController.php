<?php

namespace App\Http\Controllers;

use App\Models\Regency;
use Illuminate\Http\Request;

class DatamasterController extends Controller
{
    public function city($id)
    {
        $city = Regency::where('idprov', $id)->where('status', 1)->get();
        return response()->json($city);
    }
}
