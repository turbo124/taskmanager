<?php

namespace App\Rules\Refund;

use Illuminate\Contracts\Validation\Rule;
use App\Invoice;
use App\Paymentable;
use App\Payment;

class InvoiceRefundValidation implements Rule
{
    private $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if (!isset($this->request['invoices'])) {
            return true;
        }

        if (!$this->validate($this->request['invoices'])) {
            return false;
        }

        return true;
    }

    private function validate(array $arrInvoices): bool
    {
        $invoice_total = 0;
        $this->customer = null;

        foreach ($arrInvoices as $arrInvoice) {

            $invoice = $this->validateInvoice($arrInvoice['invoice_id']);

            if (!$invoice) {
                return false;
            }

            if (!$this->validateCustomer($invoice)) {
                return false;
            }


            $invoice_total += $invoice->total;
        }

        if ($invoice_total > $this->request['amount']) {

            return false;
        }

        return true;
    }

    private function validateInvoice(int $invoice_id)
    {
        $invoice = Invoice::whereId($invoice_id)->first();

        // check allowed statuses here
        if (!$invoice || $invoice->is_deleted) {
            $this->validationFailures[] = 'Invoice is not a valid invoice';
            return false;
        }

        /*if($invoice->balance <= 0) {
            $this->validationFailures[] = 'The invoice has already been paid';
            return false;
        }*/

        if (!in_array($invoice->status_id, [Invoice::STATUS_PAID])) {
            $this->validationFailures[] = 'Invoice is at the wrong status';
            return false;
        }

        $paymentable = Paymentable::where('paymentable_id', $invoice_id)->where('payment_id', $this->request['id'])->first();

        $payment = Payment::whereId($this->request['id'])->first();

        if (!$payment) {
            return false;
        }

        $allowed_invoices = $payment->invoices->pluck('id')->toArray();

        if (!in_array($invoice_id, $allowed_invoices)) {
            $this->validationFailures[] = 'Invoice is invalid';
            return false;
        }

        if ($this->request['amount'] + $paymentable->refunded > $payment->amount) {
            return false;
        }

        return $invoice;
    }

    private function validateCustomer(Invoice $invoice)
    {

        if ($this->customer === null) {
            $this->customer = $invoice->customer;
            return true;
        }

        if ($this->customer->id !== $invoice->customer->id) {
            $this->validationFailures[] = 'Cannot create invoice for different customers';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->validationFailures;
    }
}
