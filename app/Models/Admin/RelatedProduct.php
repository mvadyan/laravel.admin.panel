<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $table = 'related_products';

    protected $fillable = [
        'product_id',
        'related_id',
    ];
}
