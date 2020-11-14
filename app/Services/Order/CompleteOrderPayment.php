<?php
class CompleteOrderPayment
{
    private function execute()
    {
       
        
       

       
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
