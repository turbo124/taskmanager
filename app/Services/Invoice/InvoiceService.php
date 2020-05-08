<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\InvoiceRepository;
use App\Payment;
use App\Services\Invoice\ReverseInvoicePayment;
use App\Services\Invoice\MarkPaid;
use Illuminate\Support\Carbon;
use App\Services\Invoice\ApplyPayment;
use App\Events\Invoice\InvoiceWasPaid;
use App\Events\Invoice\InvoiceWasEmailed;
use App\Services\ServiceBase;

class InvoiceService extends ServiceBase
{
    protected $invoice;

    protected $customer_service;

    private $payment_service;

    public function __construct(Invoice $invoice)
    {
        $config = [
            'email' => $invoice->customer->getSetting('should_email_invoice'),
            'archive' => $invoice->customer->getSetting('should_archive_invoice')
        ];

        parent::__construct($invoice, $config);
        $this->invoice = $invoice;
    }

    /**
     * @return Invoice
     */
    public function cancelInvoice(): Invoice
    {
        $this->invoice = (new CancelInvoice($this->invoice))->run();

        return $this->invoice;
    }

    public function sendReminders()
    {
        (new SendReminders($this->invoice->account->getSettings(), $this->invoice))->run();

        return $this;
    }

    /**
     * @param CreditRepository $credit_repo
     * @param PaymentRepository $payment_repo
     * @return Invoice
     */
    public function reverseInvoicePayment(CreditRepository $credit_repo, PaymentRepository $payment_repo)
    {
       return (new ReverseInvoicePayment($this->invoice, $credit_repo, $payment_repo))->run();
    }

    public function getPdf($contact = null)
    {
        return (new GetPdf($this->invoice, $contact))->run();
    }

    public function markPaid(InvoiceRepository $invoice_repo, PaymentRepository $payment_repo)
    {
        $invoice = (new MarkPaid($this->invoice, $payment_repo))->run();

        event(new InvoiceWasPaid($invoice));

        // trigger
        $subject = trans('texts.invoice_paid_subject');
        $body = trans('texts.invoice_paid_body');
        $this->trigger($subject, $body, $invoice_repo);

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

        event(new InvoiceWasPaid($invoice));

        // trigger
        $subject = trans('texts.invoice_paid_subject');
        $body = trans('texts.invoice_paid_body');
        $this->trigger($subject, $body, (new InvoiceRepository(new Invoice)));

        return $invoice;
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject, $body, $template = 'invoice'): ?Invoice
    {
        if (!$this->sendInvitationEmails($subject, $body, $template, $contact)) {
            return null;
        }

        event(new InvoiceWasEmailed($this->invoice->invitations->first()));
        return $this->invoice;
    }

    public function calculateInvoiceTotals(): Invoice
    {
        return $this->calculateTotals($this->invoice);
    }
}
