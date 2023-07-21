<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'blw_testimoni';


    public $timestamps = true;

    const UPDATED_AT = null;

    const CREATED_AT = 'tgl';

    protected $fillable = [
        'tgl',
        'status',
        'nama',
        'foto',
        'komentar',
        'jabatan',
        'id_produk'
    ];

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    public function getImageUrlAttribute()
    {
        if (strpos($this->foto, "public") !== false) {
            return storageAsset($this->foto);
        }
        return storageAsset('public/' . $this->foto);
    }
}
