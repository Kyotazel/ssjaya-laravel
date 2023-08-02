<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sales_id',
        'status'
    ];

    const STATUS = [
        'PENDING',
        'APPROVED',
        'REJECTED',
        'ARCHIVED'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function pharmacies()
    {
        return $this->hasMany(DepositReportPharmacy::class);
    }
}
