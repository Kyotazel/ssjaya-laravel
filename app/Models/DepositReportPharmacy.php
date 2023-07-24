<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositReportPharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_report_id',
        'pharmacy_id'
    ];

    public function despositReport()
    {
        return $this->belongsTo(DepositReport::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharamcy_id', 'id_apotek');
    }

    public function products()
    {
        return $this->hasMany(DepositReportPharmacyProduct::class);
    }
}
