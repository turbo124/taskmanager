<?php

namespace App\Helpers\Refund;

use App\Events\Payment\PaymentWasRefunded;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;

class InvoiceRefund extends BaseRefund
{

    /**
     * InvoiceRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repository
     */
    public function __construct(Payment $payment, array $data, CreditRepository $credit_repository)
    {
        parent::__construct($payment, $data, $credit_repository);
    }

    /**
     * @return Payment
     */
    public function refund($payment_invoices)
    {
        $ids = array_column($payment_invoices, 'invoice_id');
        $invoices = Invoice::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($payment_invoices as $payment_invoice) {
            if (!isset($invoices[$payment_invoice['invoice_id']])) {
                continue;
            }

            $invoice = $invoices[$payment_invoice['invoice_id']];

            $this->createLineItem($payment_invoice['amount'], $invoice);
            $this->increaseRefundAmount($payment_invoice['amount']);
            $invoice->adjustInvoices($payment_invoice['amount']);
            $this->updateRefundedAmountForInvoice($invoice, $payment_invoice['amount']);
        }

        $this->save();

        return $this->payment;
    }

    /**
     * @param Invoice $invoice
     * @param $amount
     * @return bool
     */
    private function updateRefundedAmountForInvoice(Invoice $invoice, $amount): bool
    {
        $paymentable_invoice = Paymentable::wherePaymentableId($invoice->id)->first();
        $paymentable_invoice->refunded += $amount;
        $paymentable_invoice->save();
        return true;
    }
}