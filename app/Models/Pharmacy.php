<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $table = 'sales_apotek';

    protected $primaryKey = 'id_apotek';

    public $timestamps = false;

    protected $fillable = [
        'id_sales',
        'nama_apotek',
        'provinsi',
        'kota',
        'kecamatan',
        'alamat',
        'keterangan',
        'produk'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'id_sales', 'id_sales');
    }

    public function city()
    {
        return $this->belongsTo(Regency::class, 'kota', 'id');
    }
}
