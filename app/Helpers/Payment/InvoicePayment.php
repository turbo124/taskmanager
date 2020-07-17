<?php

namespace App\Helpers\Payment;

use App\Events\Payment\PaymentWasRefunded;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;

class InvoicePayment extends BasePaymentProcessor
{
    /**
     * @var array|mixed
     */
    private array $invoices;

    /**
     * InvoicePayment constructor.
     * @param array $data
     * @param Payment $payment
     * @param PaymentRepository $payment_repo
     */
    public function __construct(array $data, Payment $payment, PaymentRepository $payment_repo)
    {
        parent::__construct($payment, $payment_repo, $data);
        $this->invoices = $data['invoices'];
    }

    /**
     * @return Payment
     */
    public function process($objCreditPayment = null)
    {
        $invoices = Invoice::whereIn('id', array_column($this->invoices, 'invoice_id'))->get();
        $payment_invoices = collect($this->invoices)->keyBy('invoice_id')->toArray();

        foreach ($invoices as $invoice) {
            if (empty($payment_invoices[$invoice->id])) {
                continue;
            }

            $amount = $payment_invoices[$invoice->id]['amount'];

            $this->payment->attachInvoice($invoice, $amount);

            $this->increasePaymentAmount($amount);

            $invoice->service()->makeInvoicePayment($this->payment, $amount);
        }

        $this->reduceCreditedAmount($objCreditPayment);
        $this->save();

        return $this->payment;
    }

    /**
     * @param null $objCreditPayment
     * @return bool
     */
    private function reduceCreditedAmount($objCreditPayment = null)
    {
        if ($objCreditPayment === null || $objCreditPayment->getAmount() <= 0) {
            return true;
        }

        $this->reducePaymentAmount($objCreditPayment->getAmount());
        return true;
    }
}
