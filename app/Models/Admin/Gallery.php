<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'id',
        'product_id',
        'img',
    ];
}
