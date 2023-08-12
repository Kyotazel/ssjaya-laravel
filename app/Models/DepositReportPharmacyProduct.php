<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositReportPharmacyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_report_pharmacy_id',
        'pharmacy_product_id',
        'stock',
        'price'
    ];

    public function depositReportPharmacy()
    {
        return $this->belongsTo(DepositReportPharmacy::class);
    }

    public function pharmacyProduct()
    {
        return $this->belongsTo(PharmacyProduct::class);
    }

    public function product()
    {
        return $this->pharmacyProduct->product;
    }
}
