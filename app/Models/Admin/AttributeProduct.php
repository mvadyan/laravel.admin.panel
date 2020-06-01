<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    protected $table = 'attribute_products';

    protected $fillable = [
        'attr_id',
        'product_id',
    ];
}
