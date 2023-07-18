<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitReport extends Model
{
    use HasFactory;

    protected $table = 'laporan_kunjungan';

    protected $primaryKey = 'id_laporan';

    public $timestamps = true;

    const UPDATED_AT = null;

    const CREATED_AT = 'timestamp';

    protected $fillable = [
        'id_sales',
        'images',
        'status',
        'timestamp',
        'id_apotek'
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'id_apotek', 'id_apotek');
    }

    public function getImageUrlAttribute()
    {
        if (strpos($this->image, "public")) {
            return storageAsset($this->image);
        }
        return storageAsset('public/' . $this->images);
    }
}
