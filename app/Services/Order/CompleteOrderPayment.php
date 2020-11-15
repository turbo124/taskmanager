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
       $payment = Payment::where('id', $this->order->payment_id)->first();
       $transaction_ref = $this->capturePayment();
       
       if(!$transaction_ref) {
           return false;
       }

       $this->updatePayment($payment, $transaction_ref);

       $this->updateInvoice();
       $this->updateCustomer();
       $this->updateOrder();

       return true;
    }
   
    private function capturePayment(Payment $payment)
    {
        //TODO
        $customer_gateway = CustomerGateway::where('company_gateway_id', $payment->company_gateway_id)->first();
        $company_gateway = $customer_gateway->company_gateway;
        $objGateway = (new GatewayFactory($customer_gateway, $company_gateway))->create($payment->customer);
       
        $objGateway->capturePayment($payment);
    }

    private function updateOrder (): bool
    {
        $this->order->reduceBalance($this->data['amount']);
        $this->order->payment_taken = true;
        $this->order->save()
        return true;
    }

    private function updatePayment(Payment $payment, $transaction_ref): bool
    {
        // update payment
        $payment->setStatus(Payment::STATUS_COMPLETED);
        $payment->save();
        return true;
    }

    private function updateInvoice(): Invoice
    {
         // update invoice
        $invoice = Invoice::where('id', $this->order->invoice_id)->first();
        $invoice->reduceBalance($amount);
        $invoice->setStatus(Invoice::STATUS_PAID);
        $invoice->save();

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
