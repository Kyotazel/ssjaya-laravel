<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    
    protected $table = 'blw_prov';

    public function cities()
    {
        return $this->hasMany(Regency::class);
    }
}
