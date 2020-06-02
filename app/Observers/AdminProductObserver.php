<?php

namespace App\Observers;

use App\Models\Admin\Product;
use Illuminate\Support\Carbon;

class AdminProductObserver
{
    /**
     * @param Product $product
     */
    public function creating(Product $product)
    {
        $this->setAlias($product);
    }

    /**
     * @param Product $product
     */
    public function saving(Product $product)
    {
        $this->setPulishedAt($product);
    }

    /**
     * @param Product $product
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * @param Product $product
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * @param Product $product
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * @param Product $product
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * @param Product $product
     */
    public function forceDeleted(Product $product)
    {
        //
    }

    /**
     * @param Product $product
     */
    public function setAlias(Product $product)
    {
        if (empty($product->alias)) {
            $product->alias = \Str::slug($product->title);
            $check = Product::where('alias', $product->alias)->exists();

            if ($check) {
                $product->alias = \Str::slug($product->title) . time();
            }
        }
    }

    public function setPulishedAt (Product $product)
    {
        $needSetPublished = empty($product->updated_at) || !empty($product->updated_at);

        if($needSetPublished) {
            $product->updated_at = Carbon::now();
        }
    }
}
