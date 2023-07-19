<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $table = 'blw_kab';

    protected $fillable = [
        'idprov',
        'nama',
        'status'
    ];

    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(Province::class, 'idprov', 'id');
    }
}
