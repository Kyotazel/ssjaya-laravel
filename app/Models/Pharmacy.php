<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $table = 'sales_apotek';

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'id_sales', 'id_sales');
    }
}
