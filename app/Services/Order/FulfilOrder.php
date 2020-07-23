<?php

namespace App\Services\Order;

use App\Events\Order\OrderWasBackordered;
use App\Factory\CloneOrderToInvoiceFactory;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Quote;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;

class FulfilOrder
{
    /**
     * @var \App\Models\Order
     */
    private Order $order;

    /**
     * @var OrderRepository
     */
    private OrderRepository $order_repository;

    /**
     * FulfilOrder constructor.
     * @param \App\Models\Order $order
     */
    public function __construct(Order $order, OrderRepository $order_repository)
    {
        $this->order = $order;
    }

    /**
     * @param $quote
     * @return mixed
     */
    public function execute()
    {
        /********* Check stock for each item and determine if should be backordered or partially sent **********/
        // if out of stock items is more than 0 and no partial orders allowed fail order
        // if out of stock and backorders not allowed fail order

        $order = $this->order->service()->checkStock();

        if (empty($order) || ($order->status_id === Order::STATUS_BACKORDERED && $order->customer->getSetting(
                    'allow_backorders'
                ) === false)) {
            $order->setStatus(Order::STATUS_ORDER_FAILED);
        }

        return $this->order;
    }
}
