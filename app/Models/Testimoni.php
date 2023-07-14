<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'blw_testimoni';

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    public function getImageUrlAttribute()
    {
        return storageAsset('public/'.$this->foto);
    }
}
