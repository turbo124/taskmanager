<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductAttribute;

class CompleteOrderPayment
{
    /**
     * @var Order
     */
    private Order $order;

    /**
     * CompleteOrderPayment constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    private function execute(): bool
    {
       if(!$this->capturePayment()) {
           return false;
       }

       $this->updateInvoice();
       $this->updatePayment();
       $this->updateCustomer();
       $this->updateOrder();

       return true;
    }
   
    private function capturePayment()
    {
        //TODO
    }

    private function updateOrder (): bool
    {
         $this->order->reduceBalance($this->data['amount']);
        $this->order->payment_taken = true;
        $this->order->save()
        return true;
    }

    private function updatePayment(): Payment
    {
        // update payment
        $payment = Payment::where('id', $this->order->payment_id)->first();
        $payment->setStatus(Payment::STATUS_COMPLETED);
        $payment->save();
        return $payment;
    }

    private function updateInvoice(): Invoice
    {
         // update invoice
        $invoice = Invoice::where('id', $this->order->invoice_id)->first();
        $invoice->reduceBalance($amount);

        // TODO update status

        return $invoice;
    }

    private function updateCustomer(): Customer
    {
        $this->order->customer->reduceBalance($amount);
        $this->order->customer->increasePaidToDateAmount($amount);
        $this->order->customer->save();

        return $this->order->customer;
    }
}
