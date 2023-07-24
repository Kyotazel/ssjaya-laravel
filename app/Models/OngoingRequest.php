<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OngoingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sales_id',
        'status',
        'request_date'
    ];

    const STATUS = [
        'PENDING',
        'APPROVED',
        'REJECTED'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function pharmacies()
    {
        return $this->hasMany(OngoingRequestPharmacy::class);
    }
}
