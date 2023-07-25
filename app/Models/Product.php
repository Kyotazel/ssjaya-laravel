<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'blw_produk';

    public $timestamps = true;

    const UPDATED_AT = 'tglupdate';

    const CREATED_AT = 'tglbuat';

    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'aturan',
        'manfaat',
        'merk_photo',
        'url',
        'tglbuat',
        'tglupdate',
    ];

    // protected $appends = ['image_url', 'merk_image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image != null) {
            return $this->image->image_url;
        }

        return '';
    }

    public function getMerkImageUrlAttribute()
    {
        if (strpos($this->merk_photo, "public") !== false) {
            return storageAsset($this->merk_photo);
        }
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
