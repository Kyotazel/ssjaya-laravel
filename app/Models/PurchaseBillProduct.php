<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBillProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_bill_id',
        'product_id',
        'stock',
        'price',
    ];


    public function bill()
    {
        return $this->belongsTo(PurchaseBill::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
