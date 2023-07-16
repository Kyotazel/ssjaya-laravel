<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $table = 'blw_produk_has_sertifikasi';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return storageAsset('public/' . $this->image);
    }
}
