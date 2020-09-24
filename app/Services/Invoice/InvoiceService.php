<?php

namespace App\Services\Invoice;

use App\Events\Invoice\InvoiceWasPaid;
use App\Factory\InvoiceToRecurringInvoiceFactory;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\RecurringInvoice;
use App\Repositories\CreditRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\RecurringInvoiceRepository;
use App\Services\ServiceBase;

/**
 * Class InvoiceService
 * @package App\Services\Invoice
 */
class InvoiceService extends ServiceBase
{
    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * InvoiceService constructor.
     * @param Invoice $invoice
     */
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
        $this->invoice = (new CancelInvoice($this->invoice))->execute();

        return $this->invoice;
    }

    public function send()
    {
        $this->invoice->updateInvoiceBalance($this->invoice->balance);
    }

    /**
     * @param CreditRepository $credit_repo
     * @param PaymentRepository $payment_repo
     * @return Invoice
     */
    public function reverseInvoicePayment(CreditRepository $credit_repo, PaymentRepository $payment_repo)
    {
        return (new ReverseInvoicePayment($this->invoice, $credit_repo, $payment_repo))->execute();
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->invoice, $contact, $update))->execute();
    }

    /**
     * @param InvoiceRepository $invoice_repo
     * @param PaymentRepository $payment_repo
     * @return Invoice|null
     */
    public function createPayment(InvoiceRepository $invoice_repo, PaymentRepository $payment_repo): ?Invoice
    {
        $invoice = (new CreatePayment($this->invoice, $payment_repo))->execute();

        if (!$invoice) {
            return null;
        }

        event(new InvoiceWasPaid($invoice));

        $this->sendPaymentEmail($invoice_repo);

        return $invoice;
    }

    public function sendPaymentEmail(InvoiceRepository $invoice_repo)
    {
        // trigger
        $subject = trans('texts.invoice_paid_subject');
        $body = trans('texts.invoice_paid_body');
        $this->trigger($subject, $body, $invoice_repo);

        return true;
    }

    /**
     * @return Order
     */
    public function reverseStatus(): ?Invoice
    {
        if (!in_array($this->invoice->status_id, [Invoice::STATUS_CANCELLED, Invoice::STATUS_REVERSED])) {
            return null;
        }

        $this->invoice->date_cancelled = null;

        parent::reverseStatus();
        parent::reverseBalance();

        return $this->invoice;
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

        //event(new InvoiceWasEmailed($this->invoice->invitations->first()));
        return $this->invoice;
    }

    public function calculateInvoiceTotals(): Invoice
    {
        return $this->calculateTotals($this->invoice);
    }

    /**
     * @param array $data
     * @return RecurringInvoice|null
     */
    public function createRecurringInvoice(array $recurring): ?RecurringInvoice
    {
        if (empty($recurring)) {
            return null;
        }

        $arrRecurring['start_date'] = $recurring['start_date'];
        $arrRecurring['end_date'] = $recurring['end_date'];
        $arrRecurring['frequency'] = $recurring['frequency'];
        $arrRecurring['recurring_due_date'] = $recurring['recurring_due_date'];
        $recurringInvoice = (new RecurringInvoiceRepository(new RecurringInvoice))->save(
            $arrRecurring,
            InvoiceToRecurringInvoiceFactory::create($this->invoice)
        );

        $this->invoice->recurring_invoice_id = $recurringInvoice->id;
        $this->invoice->save();

        return $recurringInvoice;
    }
}
