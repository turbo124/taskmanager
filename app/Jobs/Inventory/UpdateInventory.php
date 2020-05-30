<?php

namespace App\Jobs\Inventory;

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
class UpdateInventory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Invoice
     */
    private Invoice $invoice;


    /**
     * UpdateInventory constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->invoice->line_items)) {
            return;
        }

        foreach ($this->invoice->line_items as $item) {
            if (empty($item->product_id) || $item->type_id !== 1) {
                continue;
            }

            if (!empty($item->attribute_id)) {
                $product_attribute = ProductAttribute::find($item->attribute_id);

                if ($this->invoice->customer->getSetting('inventory_enabled') === true) {
                    $product_attribute->reduceQuantityReserved($item->quantity);
                }

                if ($this->invoice->customer->getSetting('should_update_inventory')) {
                    $product_attribute->reduceQuantityAvailiable($item->quantity);
                }

                continue;
            }

            $product = Product::where('id', $item->product_id)->first();

            if (empty($product) || $product->count() === 0) {
                continue;
            }

            if ($this->invoice->customer->getSetting('should_update_inventory')) {
                $product->reduceQuantityAvailiable($item->quantity);
            }

            if ($this->invoice->customer->getSetting('inventory_enabled') === true) {
                $product->reduceQuantityReserved($item->quantity);
            }
        }
    }
}