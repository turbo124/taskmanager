<?php

namespace App\Components\Payment\Invoice;

use App\Events\Invoice\InvoiceWasPaid;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\InvoiceRepository;

/**
 * Class RecalculateInvoice
 * @package App\Services\Invoice
 */
class RecalculateInvoice
{

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var Payment
     */
    private Payment $payment;
    private $payment_amount;

    public function __construct(Invoice $invoice, Payment $payment, $payment_amount)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->payment_amount = $payment_amount;
    }

    public function execute()
    {
        if (!empty($this->invoice->gateway_fee)) {
            $this->payment_amount += $this->invoice->gateway_fee;
        }

        $this->updateInvoiceTotal();

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            return $this->updateInvoice(true);
        }

        if ($this->payment_amount > $this->invoice->balance) {
            return $this->invoice;
        }

        $this->updateInvoice();

        return $this->invoice;
    }

    /**
     * @return Invoice
     */
    private function updateInvoiceTotal(): Invoice
    {
        $invoice = $this->payment->invoices->where('id', $this->invoice->id)->first();
        $invoice->pivot->amount = $this->payment_amount;
        $invoice->pivot->save();
        return $invoice;
    }

    /**
     * @return Invoice
     */
    private function updateInvoice($partial = false): Invoice
    {
        if ($partial) {
            $this->resetPartialInvoice();
            $this->invoice->setDueDate();
        }

        $this->invoice->reduceBalance($this->payment_amount);

        $status = $this->invoice->partial && $this->invoice->partial > 0 ? Invoice::STATUS_PARTIAL : Invoice::STATUS_PAID;
        $this->invoice->setStatus($status);

        $this->save();

        return $this->invoice;
    }

    /**
     * @return bool
     */
    private function resetPartialInvoice(): bool
    {
        $balance_adjustment = $this->invoice->partial > $this->payment_amount ? $this->payment_amount : $this->invoice->partial;
        $balance_adjustment = $this->invoice->partial == $this->payment_amount ? 0 : $balance_adjustment;

        if ($balance_adjustment > 0) {
            $this->invoice->partial -= $balance_adjustment;
            return true;
        }

        $this->invoice->partial = null;
        $this->invoice->partial_due_date = null;

        return true;
    }

    private function save()
    {
        $this->invoice->save();

        event(new InvoiceWasPaid($this->invoice, $this->payment));

        $this->invoice->service()->sendPaymentEmail(new InvoiceRepository($this->invoice));
    }
}