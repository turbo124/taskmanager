<?php

namespace App\Services\Order;

use App\Invoice;
use App\Order;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Services\Quote\ConvertOrder;
use Carbon\Carbon;
use App\Services\ServiceBase;

class OrderService extends ServiceBase
{
    protected $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getPdf($contact)
    {
        return (new GetPdf($this->order, $contact))->run();
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'order')
    {
        return (new OrderEmail($this->order, $subject, $body, $template, $contact))->run();
    }

    /**
     * @param InvoiceRepository $invoice_repo
     * @param OrderRepository $order_repo
     * @return OrderService
     */
    public function convert(InvoiceRepository $invoice_repo, OrderRepository $order_repo): OrderService
    {
        $this->order->setStatus(Order::STATUS_COMPLETE);
        
        $invoice = (new ConvertOrder($invoice_repo, $this->order))->run();

        $this->order->setInvoiceId($invoice->id);
        $this->order->save();

        return $this;

    }

    public function calculateInvoiceTotals(): Order
    {
        return $this->calculateTotals($this->order);
    }
}
