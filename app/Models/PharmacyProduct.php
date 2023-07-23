<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'product_id',
        'stock'
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharamcy_id', 'id_apotek');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
