<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'code',
        'image_url',
    ];

    public function getImageUrlAttribute($value)
    {
        return storageAsset($value);
    }

    public function products()
    {
        return $this->hasMany(PurchaseBillProduct::class);
    }
}
