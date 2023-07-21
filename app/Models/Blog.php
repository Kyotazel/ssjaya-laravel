<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blw_blog';

    public $timestamps = true;

    const UPDATED_AT = null;

    const CREATED_AT = 'tgl';

    protected $fillable = [
        'judul',
        'id_category',
        'id_produk',
        'img',
        'konten',
        'tgl',
        'url',
        'views'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (strpos($this->img, "public") !== false) {
            return storageAsset($this->img);
        }
        return storageAsset('public/' . $this->img);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'id_category', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }
}
