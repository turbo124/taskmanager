<?php

namespace App\Jobs\Inventory;

use App\Credit;
use App\Invoice;
use App\Product;
use App\ProductAttribute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Capsule\Eloquent;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateInventory
 * @package App\Jobs\Inventory
 */
class ReverseInventory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Credit
     */
    private Credit $credit;


    /**
     * ReverseInventory constructor.
     * @param Credit $credit
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
    }

    /**
     * Execute the job.
     *
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->credit->line_items)) {
            return;
        }

        foreach ($this->credit->line_items as $item) {
            if (empty($item->product_id) || $item->type_id !== 1) {
                continue;
            }

            if (!empty($item->attribute_id)) {
                $product_attribute = ProductAttribute::find($item->attribute_id);

                if ($this->credit->customer->getSetting('should_update_inventory')) {
                    $product_attribute->increaseQuantityAvailiable($item->quantity);
                }

                continue;
            }

            $product = Product::where('id', $item->product_id)->first();

            if (empty($product) || $product->count() === 0) {
                continue;
            }

            if ($this->credit->customer->getSetting('should_update_inventory')) {
                $product->increaseQuantityAvailiable($item->quantity);
            }
        }
    }
}