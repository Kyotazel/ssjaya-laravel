<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'app_user';

    public $timestamps = true;

    const UPDATED_AT = 'updated_date';

    const CREATED_AT = 'created_date';

    protected $fillable = [
        'name',
        'username',
        'password',
        'is_admin',
        'status',
        'is_delete',
        'last_login'
    ];

    protected $hidden = ['password'];
}
