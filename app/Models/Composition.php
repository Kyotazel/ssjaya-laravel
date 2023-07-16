<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $table = 'blw_produk_has_komposisi';

    public function getImageUrlAttribute()
    {
        return storageAsset('public/' . $this->image);
    }
}
