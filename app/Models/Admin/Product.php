<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'title',
        'alias',
        'content',
        'price',
        'old_price',
        'status',
        'keywords',
        'description',
        'img',
        'hit',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filterAttr()
    {
        return $this->hasMany('App\Models\Admin\AttributeProduct', 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relatedProducts()
    {
        return $this->hasMany('App\Models\Admin\RelatedProduct', 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery()
    {
        return $this->hasMany('App\Models\Admin\Gallery', 'product_id', 'id');
    }
}
