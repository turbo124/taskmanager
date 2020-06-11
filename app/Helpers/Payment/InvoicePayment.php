<?php

namespace App\Helpers\Refund;

use App\Events\Payment\PaymentWasRefunded;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;

class InvoiceRefund extends BasePaymentProcessor
{
    private array $invoices;

    /**
     * InvoiceRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repository
     * @param array $payment_invoices
     */
    public function __construct(array $invoices, Payment $payment, PaymentRepository $payment_repo)
    {
        parent::__construct($payment, $payment_repo);
        $this->invoices = $invoices;
    }

    /**
     * @return Payment
     */
    public function process($objCreditRefund = null)
    {
        $invoices = Invoice::whereIn('id', array_column($this->invoices, 'invoice_id'))->get();
        $payment_invoices = collect($this->invoices)->keyBy('invoice_id')->toArray();

        foreach ($invoices as $invoice) {
           if (empty($data['invoices'][$invoice->id])) {
                continue;
            }

            $this->payment->attachInvoice($invoice);

            $amount = $payment_invoices[$invoice->id]['amount'];
            $this->increasePaymentAmount($amount);

            $invoice->service()->makeInvoicePayment($payment, $amount);
        }

        $this->reduceCreditedAmount();
        $this->save();

        return $this->payment;
    }

    private function reduceCreditedAmount($objCreditRefund = null)
    {
        if($objCreditRefund === null || $objCreditRefund->getAmount() <= 0) {
            return true;
        }

        $this->reducePaymentAmount($objCreditRefund->getAmount());
        return true;
    }
}
