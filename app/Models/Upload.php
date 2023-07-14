<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'blw_upload';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return storageAsset('public/' . $this->nama);
    }
}
