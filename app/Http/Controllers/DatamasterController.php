<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Regency;
use App\Models\Sales;
use Illuminate\Http\Request;

class DatamasterController extends Controller
{
    public function city($id)
    {
        $city = Regency::where('idprov', $id)->where('status', 1)->get();
        return response()->json($city);
    }

    public function pharmacy($id)
    {
        $sales = Sales::where('id', $id)->first();
        $pharmacy = Pharmacy::where('id_sales', $sales->id_sales)
            ->with(['products.product'])
            ->orderBy('nama_apotek', 'asc')
            ->get();
        return response()->json($pharmacy);
    }
}
