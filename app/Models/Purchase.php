<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sales_id',
        'pharmacy_id',
        'status',
        'is_archived',
        'white_purchase',
        'yellow_purchase'
    ];

    protected $casts = [
        'is_archived' => 'boolean'
    ];

    const STATUS = [
        self::LUNAS,
        self::BELUMLUNAS,
    ];

    const LUNAS = 'LUNAS';
    const BELUMLUNAS = 'BELUM LUNAS';

    public function getWhitePurchaseAttribute($value)
    {
        return storageAsset($value);
    }

    public function getYellowPurchaseAttribute($value)
    {
        return storageAsset($value);
    }

    public function products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id', 'id_apotek');
    }

    public function bills()
    {
        return $this->hasMany(PurchaseBill::class);
    }
}
