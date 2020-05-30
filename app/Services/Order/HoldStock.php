<?php

namespace App\Services\Order;

use App\Events\Order\OrderWasBackordered;
use App\Events\Order\OrderWasHeld;
use App\Factory\CloneOrderToInvoiceFactory;
use App\Invoice;
use App\Order;
use App\Product;
use App\ProductAttribute;
use App\Quote;
use App\Repositories\InvoiceRepository;

class HoldStock
{
    /**
     * @var Order
     */
    private Order $order;

    /**
     * HoldStock constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param $quote
     * @return mixed
     */
    /**
     * @param $quote
     * @return mixed
     */
    public function execute()
    {
        if (empty($this->order->line_items)) {
            return $this->order;
        }

        foreach ($this->order->line_items as $item) {
            if (empty($item->product_id) || $item->type_id !== 1) {
                continue;
            }

            if (!empty($item->attribute_id)) {
                $product_attribute = ProductAttribute::find($item->attribute_id);
                $product_attribute->increaseQuantityReserved($item->quantity);
                continue;
            }

            $product = Product::where('id', $item->product_id)->first();

            if (empty($product) || $product->count() === 0) {
                continue;
            }

            $product->increaseQuantityReserved($item->quantity);
        }

        return $this->order;
    }
}
