<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
