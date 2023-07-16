<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blw_blog';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return storageAsset('public/'.$this->img);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'id_category', 'id');
    }
}
