<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'blw_upload';

    public $timestamps = true;

    const CREATED_AT = 'tgl';

    const UPDATED_AT = NULL;

    protected $fillable = [
        'idproduk',
        'jenis',
        'nama',
        'tgl',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (strpos($this->nama, "public") !== false) {
            return storageAsset($this->nama);
        }
        return storageAsset('public/' . $this->nama);
    }
}
