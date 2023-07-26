<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OngoingRequestPharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'ongoing_request_id',
        'pharmacy_id'
    ];

    protected $withSum = ['products:stock'];

    public function ongoingRequest()
    {
        return $this->belongsTo(OngoingRequest::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id', 'id_apotek');
    }

    public function products()
    {
        return $this->hasMany(OngoingRequestPharmacyProduct::class);
    }
}
