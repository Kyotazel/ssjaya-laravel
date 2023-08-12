<?php

namespace App\Http\Controllers;

use App\Models\DepositReport;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Regency;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function pharmacyReport($id)
    {
        $sales = Sales::where('id', $id)->first();
        $pharmacy = Pharmacy::where('id_sales', $sales->id_sales)->pluck('id_apotek');
        $pharamcyProduct = PharmacyProduct::whereIn('pharmacy_id', $pharmacy)
            ->where('stock', '!=', 0)
            ->with(['product:id,nama', 'pharmacy:id_apotek,nama_apotek'])
            ->get();

        $depositReport = DepositReport::where(['sales_id' => $id, 'status' => 'PENDING'])->get();
        if (!($depositReport->isEmpty())) {
            return response()->json(['message' => 'Terdapat Setoran Sales ini yang masih berlaku, ubah status terlebih dahulu'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($pharamcyProduct);
    }
}
