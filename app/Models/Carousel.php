<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    protected $table = 'carousel';

    protected $fillable = [
        'photo',
        'status'
    ];

    public $timestamps = false;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (strpos($this->photo, "public") !== false) {
            return storageAsset($this->photo);
        }
        return storageAsset('public/' . $this->photo);
    }
}
