<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'blw_prov';

    protected $fillable = [
        'nama',
        'status'
    ];

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(Regency::class);
    }
}
