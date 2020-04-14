<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderProducts
 * @package App\Models\Admin
 */
class OrderProducts extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_products';

    /**
     * @var array
     */
    protected $fillable = [
      'order_id',
      'product_id',
      'qty',
      'title',
      'price',
    ];
}
