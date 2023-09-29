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
        'yellow_purchase',
        'date'
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

    protected static function booted()
    {
        static::deleting(function (Purchase $purchase) { // before delete() method call this

            foreach ($purchase->bills as $bill) {
                $bill->products()->delete();
            }
            $purchase->bills()->delete();
            $purchase->products()->delete();
        });
    }

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
