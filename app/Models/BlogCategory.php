<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blw_blog_category';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'color'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'id_category', 'id');
    }
}
