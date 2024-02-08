<?php

namespace App\Observers;

use App\Product;
use App\Jobs\IndexProductElasticsearchJob;
use App\Jobs\RemoveProductElasticsearchJob;
use App\Models\Product as ModelsProduct;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function created(ModelsProduct $product)
    {
        dispatch(new IndexProductElasticsearchJob($product));
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function updated(ModelsProduct $product)
    {
        dispatch(new IndexProductElasticsearchJob($product));
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleted(ModelsProduct $product)
    {
        dispatch(new RemoveProductElasticsearchJob($product->id));
    }
}
