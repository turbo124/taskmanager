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
    private array $payment_invoices;

    /**
     * InvoiceRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repository
     * @param array $payment_invoices
     */
    public function __construct(
        Payment $payment,
        array $data,
        CreditRepository $credit_repository,
        array $payment_invoices
    ) {
        parent::__construct($payment, $data, $credit_repository);
        $this->payment_invoices = $payment_invoices;
    }

    /**
     * @param CreditRefund|null $objCreditRefund
     * @return Payment
     */
    public function refund(CreditRefund $objCreditRefund = null)
    {
        $ids = array_column($this->payment_invoices, 'invoice_id');
        $invoices = Invoice::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($this->payment_invoices as $payment_invoice) {
            if (!isset($invoices[$payment_invoice['invoice_id']])) {
                continue;
            }

            $invoice = $invoices[$payment_invoice['invoice_id']];

            $this->createLineItem($payment_invoice['amount'], $invoice);
            $this->increaseRefundAmount($payment_invoice['amount']);
            $invoice->adjustInvoices($payment_invoice['amount']);
            $this->updateRefundedAmountForInvoice($invoice, $payment_invoice['amount']);
        }

        $this->reduceCreditedAmount();
        $this->save();

        return $this->payment;
    }

    private function reduceCreditedAmount($objCreditRefund = null)
    {
        if ($objCreditRefund === null || $objCreditRefund->getAmount() <= 0) {
            return true;
        }

        $this->reduceRefundAmount($objCreditRefund->getAmount());
        return true;
    }

    /**
     * @param Invoice $invoice
     * @param $amount
     * @return bool
     */
    private function updateRefundedAmountForInvoice(Invoice $invoice, $amount): bool
    {
        //TODO need to check paymentable type
        $paymentable_invoice = Paymentable::wherePaymentableId($invoice->id)->wherePaymentableType(
            'App\Invoice'
        )->first();
        $paymentable_invoice->refunded += $amount;
        $paymentable_invoice->save();
        return true;
    }
}
