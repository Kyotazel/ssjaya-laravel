<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $table = 'blw_produk_has_komposisi';

    protected $fillable = [
        'id_produk',
        'nama',
        'image'
    ];

    public $timestamps = false;

    public function getImageUrlAttribute()
    {
        if (strpos($this->image, "public") !== false) {
            return storageAsset($this->image);
        }
        return storageAsset('public/' . $this->image);
    }
}
