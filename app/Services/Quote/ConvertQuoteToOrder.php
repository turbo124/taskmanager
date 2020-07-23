<?php

namespace App\Services\Quote;

use App\Factory\CloneQuoteToInvoiceFactory;
use App\Factory\CloneQuoteToOrderFactory;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\QuoteRepository;

/**
 * Class ConvertQuote
 * @package App\Services\Quote
 */
class ConvertQuoteToOrder
{
    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var OrderRepository
     */
    private OrderRepository $order_repo;

    /**
     * ConvertQuoteToOrder constructor.
     * @param Quote $quote
     * @param OrderRepository $order_repository
     */
    public function __construct(Quote $quote, OrderRepository $order_repository)
    {
        $this->quote = $quote;
        $this->order_repo = $order_repository;
    }

    /**
     * @return \App\Models\Order|null
     */
    public function execute(): ?Order
    {
        if (!empty($this->quote->order_id) || $this->quote->status_id === Quote::STATUS_EXPIRED) {
            return null;
        }

        $order = CloneQuoteToOrderFactory::create(
            $this->quote,
            $this->quote->user,
            $this->quote->account
        );

        $order = $this->order_repo->save(
            [
                'status_id' => Order::STATUS_DRAFT
            ],
            $order
        );

        $this->quote->setOrderId($order->id);
        $this->quote->setStatus(Quote::STATUS_ON_ORDER);
        $this->quote->save();

        return $order;
    }
}
