<?php

namespace App\Services\Order;

use App\Invoice;
use App\Events\Order\OrderWasDispatched;
use App\Events\Order\OrderWasEmailed;
use App\Order;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use App\Services\ServiceBase;
use App\Services\Order\ConvertOrder;

class OrderService extends ServiceBase
{
    protected $order;


    public function __construct(Order $order)
    {
        parent::__construct($order);
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
    public function sendEmail($contact = null, $subject, $body, $template = 'order'): ?Order
    {
        if(!$this->sendInvitationEmails($contact, $subject, $body, $template)) {
            return null;
        }

        event(new OrderWasEmailed($this->order->invitations->first()));
        return $this->order;
    }

    /**
     * @param InvoiceRepository $invoice_repo
     * @param OrderRepository $order_repo
     * @return OrderService
     */
    public function dispatch(InvoiceRepository $invoice_repo, OrderRepository $order_repo): Order
    {
        $this->order->setStatus(Order::STATUS_COMPLETE);
        $this->order->save();
        
        if ($this->order->customer->getSetting('should_convert_order')) {
            $invoice = (new ConvertOrder($invoice_repo, $this->order))->run();
            $this->order->setInvoiceId($invoice->id);
            $this->order->save();
        }

        if($this->order->customer->getSetting('should_email_order')) {
            $this->sendEmail(null, trans('texts.order_dispatched_subject'), trans('texts.order_dispatched_body'));
        }

        event(new OrderWasDispatched($this->order));

        if ($this->order->customer->getSetting('should_archive_order')) {
            $order_repo->archive($this->order);
        }

        return $this->order;
    }

    public function calculateInvoiceTotals(): Order
    {
        return $this->calculateTotals($this->order);
    }
}
