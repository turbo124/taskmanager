<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\InvoiceRepository;
use App\Payment;
use App\Services\Invoice\ReverseInvoicePayment;
use App\Services\Invoice\CreatePayment;
use Illuminate\Support\Carbon;
use App\Services\Invoice\MakeInvoicePayment;
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
            'email'   => $invoice->customer->getSetting('should_email_invoice'),
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

    /**
     * @param InvoiceRepository $invoice_repo
     * @param PaymentRepository $payment_repo
     * @return Invoice|null
     */
    public function createPayment(InvoiceRepository $invoice_repo, PaymentRepository $payment_repo): ?Invoice
    {
        $invoice = (new CreatePayment($this->invoice, $payment_repo))->run();

        if (!$invoice) {
            return null;
        }

        event(new InvoiceWasPaid($invoice));

        // trigger
        $subject = trans('texts.invoice_paid_subject');
        $body = trans('texts.invoice_paid_body');
        $this->trigger($subject, $body, $invoice_repo);

        return $invoice;
    }

    /**
     * @param Payment $payment
     * @param float $payment_amount
     * @return Invoice
     */
    public function makeInvoicePayment(Payment $payment, float $payment_amount): Invoice
    {
        $invoice = (new MakeInvoicePayment($this->invoice, $payment, $payment_amount))->run();

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
