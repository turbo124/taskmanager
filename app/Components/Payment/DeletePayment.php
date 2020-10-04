
<?php

namespace App\Components\Payment;

use App\Events\Payment\PaymentWasDeleted;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Payment;

class DeletePayment
{
    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * DeletePayment constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function execute()
    {
        $this->updateCredit();
        $this->updateInvoice();
        $this->updateCustomer();
        $this->updatePayment();

        return $this->payment;
    }

    /**
     * @return bool
     */
    private function updateCredit(): bool
    {
        if ($this->payment->credits()->count() === 0) {
            return true;
        }

        $delete_status = !empty($this->payment->customer->getSetting('invoice_payment_deleted_status')) ? (int) $this->payment->customer->getSetting('credit_payment_deleted_status') : Credit::STATUS_SENT;

        foreach ($this->payment->credits as $credit) {
            if($delete_status === 100) {
                $credit->delete();
                return true;
            }

            $credit->increaseBalance($credit->total);
            $credit->setStatus($delete_status);
            $credit->save();
        }

        return true;
    }

    private function updateInvoice(): bool
    {
        if ($this->payment->invoices()->count() === 0) {
            return true;
        }

        $delete_status = !empty($this->payment->customer->getSetting('invoice_payment_deleted_status')) ? (int) $this->payment->customer->getSetting('invoice_payment_deleted_status') : Invoice::STATUS_SENT;

        foreach ($this->payment->invoices as $invoice) {
            if($delete_status === 100) {
                $invoice->delete();
                continue;
            }

            $invoice->resetBalance($invoice->pivot->amount);
            $invoice->customer->increaseBalance($invoice->pivot->amount);
            $invoice->setStatus($delete_status);

            // create transaction
            $this->createTransaction($invoice);
        }

        return true;
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    private function createTransaction(Invoice $invoice): bool
    {
        $invoice->transaction_service()->createTransaction(
            $invoice->total,
            $invoice->customer->balance,
            'Payment Deletion'
        );

        return true;
    }

    private function updateCustomer(): bool
    {
        $customer = $this->payment->customer;
        $customer->reducePaidToDateAmount($this->payment->amount);

        return true;
    }

    public function updatePayment(): bool
    {
        $this->payment->setStatus(Payment::STATUS_VOIDED);
        $this->payment->save();
        event(new PaymentWasDeleted($this->payment));

        $this->payment->delete();

        // event here

        return true;
    }
}
