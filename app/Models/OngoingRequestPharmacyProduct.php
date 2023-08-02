<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OngoingRequestPharmacyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'ongoing_request_pharmacy_id',
        'pharmacy_product_id',
        'stock',
        'price'
    ];

    public function ongoingRequestPharmacy()
    {
        return $this->belongsTo(OngoingRequest::class);
    }

    public function pharmacyProduct()
    {
        return $this->belongsTo(PharmacyProduct::class);
    }

    public function product()
    {
        return $this->pharmacyProduct->product;
    }

    public function pharmacy()
    {
        return $this->pharmacyProduct->pharmacy;
    }
}
