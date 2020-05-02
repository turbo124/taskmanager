<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\InvoiceRepository;
use App\Payment;
use App\Services\Invoice\HandleCancellation;
use App\Services\Invoice\HandleReversal;
use App\Services\Invoice\MarkPaid;
use App\Services\Invoice\UpdateBalance;
use Illuminate\Support\Carbon;
use App\Services\Invoice\ApplyPayment;
use App\Events\Invoice\InvoiceWasPaid; 
use App\Services\ServiceBase;

class InvoiceService extends ServiceBase
{
    protected $invoice;

    protected $customer_service;

    private $payment_service;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handleCancellation()
    {
        $this->invoice = (new HandleCancellation($this->invoice))->run();

        return $this;
    }

    public function sendReminders()
    {
        (new SendReminders($this->invoice->account->getSettings(), $this->invoice))->run();

        return $this;
    }

    /**
     * @param CreditRepository $credit_repo
     * @param PaymentRepository $payment_repo
     * @return $this
     */
    public function handleReversal(CreditRepository $credit_repo, PaymentRepository $payment_repo)
    {
        return (new HandleReversal($this->invoice, $credit_repo, $payment_repo))->run();  
    }

    public function getPdf($contact = null)
    {
        return (new GetPdf($this->invoice, $contact))->run();
    }

    public function markPaid(InvoiceRepository $invoice_repo, PaymentRepository $payment_repo)
    {
        $invoice = (new MarkPaid($this->invoice, $payment_repo))->run();

        $this->completePaymentWorkflow($invoice);

        return $invoice;
    }

    /**
     * Apply a payment amount to an invoice.
     * @param Payment $payment The Payment
     * @param float $payment_amount The Payment amount
     * @return RecurringInvoiceService          Parent class object
     */
    public function applyPayment(Payment $payment, float $payment_amount): Invoice
    {
        $invoice = (new ApplyPayment($this->invoice, $payment, $payment_amount))->run();
        
       $this->completePaymentWorkflow($invoice);

        return $invoice;
    }

    private function completePaymentWorkflow(Invoice $invoice): Invoice
    {
        if($invoice->customer->getSetting('should_email_invoice')) {
            $this->sendEmail(null, trans('texts.invoice_paid_subject'), trans('texts.invoice_paid_body'));
        }

        if ($invoice->customer->getSetting('should_archive_invoice')) {
            (new InvoiceRepository(new Invoice))->archive($invoice);
        }

        event(new InvoiceWasPaid($invoice));

        return $invoice;
    }


    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'invoice')
    {
        return (new InvoiceEmail($this->invoice, $subject, $body, $template, $contact))->run();
    }

    public function calculateInvoiceTotals(): Invoice
    {
        return $this->calculateTotals($this->invoice);
    }
}
