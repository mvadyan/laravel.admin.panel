<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

}
