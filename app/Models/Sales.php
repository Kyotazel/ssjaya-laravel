<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Sales extends Authenticatable
{
    use HasFactory;

    protected $table = 'sales_user';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama',
        'id_sales',
        'password',
        'nomor'
    ];

    protected $hidden = ['password'];

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class, 'id_sales', 'id_sales');
    }
}
