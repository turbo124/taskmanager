<?php

namespace App\Jobs\Product;

use App\Product;
use App\ProductAttribute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Capsule\Eloquent;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $line_items;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($line_items)
    {
        $this->line_items = $line_items;
    }

    /**
     * Execute the job.
     *
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->line_items as $item) {
            if (empty($item->product_id)) {
                continue;
            }

            $product = Product::find($item->product_id);

            if (!$product) {
                continue;
            }

            $product->price = $item->unit_price;
            $product->save();
        }
    }
}