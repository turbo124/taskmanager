<?php

namespace App\Services\Quote;

use App\Invoice;
use App\Quote;
use App\Events\Quote\QuoteWasApproved;
use App\Events\Quote\QuoteWasEmailed;
use App\Repositories\InvoiceRepository;
use App\Services\Quote\MarkSent;
use App\Repositories\QuoteRepository;
use App\Services\ServiceBase;

class QuoteService extends ServiceBase
{
    protected $quote;

    public function __construct(Quote $quote)
    {
        $config = [
            'email'   => $quote->customer->getSetting('should_email_quote'),
            'archive' => $quote->customer->getSetting('should_archive_quote')
        ];

        parent::__construct($quote, $config);
        $this->quote = $quote;
    }

    public function approve(InvoiceRepository $invoice_repo, QuoteRepository $quote_repo): ?Quote
    {
        if ($this->quote->status_id != Quote::STATUS_SENT) {
            return null;
        }

        $this->quote->setStatus(Quote::STATUS_APPROVED);
        $this->quote->save();

        if ($this->quote->customer->getSetting('should_convert_quote')) {
           (new ConvertQuote($this->quote, $invoice_repo))->execute();
        }

        event(new QuoteWasApproved($this->quote));

        // trigger
        $subject = trans('texts.quote_approved_subject');
        $body = trans('texts.quote_approved_body');
        $this->trigger($subject, $body, $quote_repo);

        return $this->quote;
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function getPdf($contact = null, $update = false)
    {
        return (new GetPdf($this->quote, $contact, $update))->execute();
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject, $body, $template = 'quote'): ?Quote
    {
        if (!$this->sendInvitationEmails($subject, $body, $template, $contact)) {
            return null;
        }

        event(new QuoteWasEmailed($this->quote->invitations->first()));
        return $this->quote;
    }

    public function calculateInvoiceTotals(): Quote
    {
        return $this->calculateTotals($this->quote);
    }

    /**
     * @param InvoiceRepository $invoice_repository
     */
    public function convertQuoteToInvoice(InvoiceRepository $invoice_repository): ?Invoice
    {
        return (new ConvertQuote($this->quote, $invoice_repository))->execute();
    }
}
