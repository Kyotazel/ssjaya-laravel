<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'blw_produk';

    protected $appends = ['image_url', 'merk_image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image->image_url;
    }

    public function getMerkImageUrlAttribute()
    {
        return storageAsset('public/' . $this->merk_photo);
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'idproduk', 'id');
    }

    public function certificates()
    {
        return $this->hasMany(Certification::class, 'id_produk', 'id');
    }

    public function compositions()
    {
        return $this->hasMany(Composition::class, 'id_produk', 'id');
    }
}
