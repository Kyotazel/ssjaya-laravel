<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'blw_produk';

    protected $appends = ['image_url', 'merk_image_attribute'];

    public function getImageUrlAttribute()
    {
        return $this->image->image_url;
    }

    public function getMerkImageUrlAttribute()
    {
        return storageAsset('public/'.$this->merk_photo);
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'idproduk', 'id');
    }

}
