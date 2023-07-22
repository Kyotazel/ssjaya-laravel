<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $table = 'blw_produk_has_sertifikasi';

    protected $fillable = [
        'id_produk',
        'image'
    ];

    public $timestamps = false;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (strpos($this->image, "public") !== false) {
            return storageAsset($this->image);
        }
        return storageAsset('public/' . $this->image);
    }
}
