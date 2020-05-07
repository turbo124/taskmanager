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
            'email' => $quote->customer->getSetting('should_email_quote'),
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
            $invoice = (new ConvertQuote($this->quote, $invoice_repo))->run();
            $this->quote->setInvoiceId($invoice->id);
            $this->quote->save();
        }

        event(new QuoteWasApproved($this->quote));

        return $this->quote;
    }

    public function getPdf($contact = null)
    {
        return (new GetPdf($this->quote, $contact))->run();
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
}
