<?php

namespace App\Services\Order;

use App\Factory\CloneOrderToInvoiceFactory;
use App\Factory\CloneQuoteToInvoiceFactory;
use App\Invoice;
use App\Order;
use App\Quote;
use App\Repositories\InvoiceRepository;

class ConvertOrder
{
    private $invoice_repo;

    public function __construct(InvoiceRepository $invoice_repo, Order $order)
    {
        $this->invoice_repo = $invoice_repo;
        $this->order = $order;
    }

    /**
     * @param $quote
     * @return mixed
     */
    public function run()
    {
        $invoice = CloneOrderToInvoiceFactory::create($this->order, $this->order->user_id, $this->order->account_id);
        $invoice->status_id = Invoice::STATUS_SENT;
        $this->invoice_repo->save([], $invoice);
        $this->invoice_repo->markSent($invoice);

        return $invoice;
    }
}
