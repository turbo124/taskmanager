<?php

namespace App\Services\Order;

use App\Events\Order\OrderWasHeld;
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
        $config = [
            'email'   => $order->customer->getSetting('should_email_order'),
            'archive' => $order->customer->getSetting('should_archive_order')
        ];

        parent::__construct($order);
        $this->order = $order;
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->order, $contact, $update))->execute();
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject, $body, $template = 'order'): ?Order
    {
        if (!$this->sendInvitationEmails($subject, $body, $template, $contact)) {
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
            $invoice = (new ConvertOrder($invoice_repo, $this->order))->execute();
            $this->order->setInvoiceId($invoice->id);
            $this->order->save();
        }

        event(new OrderWasDispatched($this->order));

        // trigger
        $subject = trans('texts.order_dispatched_subject');
        $body = trans('texts.order_dispatched_body');
        $this->trigger($subject, $body, $order_repo);

        return $this->order;
    }

    public function calculateInvoiceTotals(): Order
    {
        return $this->calculateTotals($this->order);
    }

    /**
     * @return Order
     */
    public function holdOrder(): ?Order
    {
        if ($this->order->status_id === Order::STATUS_HELD) {
            return null;
        }

        $this->order->setPreviousStatus($this->order->status_id);
        $this->order->setStatus(Order::STATUS_HELD);
        $this->order->save();

        event(new OrderWasHeld($this->order));
        return $this->order;
    }

    /**
     * @return Order
     */
    public function unholdOrder(): ?Order
    {
        if ($this->order->status_id !== Order::STATUS_HELD) {
            return null;
        }

        $this->order->setStatus($this->order->previous_status);
        $this->order->previous_status = null;
        $this->order->save();
        return $this->order;
    }

    public function fulfillOrder(OrderRepository $order_repo)
    {
        return (new FulfilOrder($this->order, $order_repo))->execute();
    }

    public function checkStock()
    {
        return (new CheckStock($this->order))->execute();
    }

    public function holdStock()
    {
        return (new HoldStock($this->order))->execute();
    }
}
