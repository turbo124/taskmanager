<?php

namespace App\Services\Quote;

use App\Factory\CloneQuoteToInvoiceFactory;
use App\Invoice;
use App\Quote;
use App\Events\Quote\QuoteWasApproved;
use App\Repositories\InvoiceRepository;
use App\Services\Quote\MarkSent;
use App\Repositories\QuoteRepository;
use App\Services\ServiceBase;

class QuoteService extends ServiceBase
{
    protected $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function approve(InvoiceRepository $invoice_repo, QuoteRepository $quote_repo): ?Quote
    {
        if ($this->quote->status_id != Quote::STATUS_SENT) {
            return null;
        }

        $this->quote->status_id = Quote::STATUS_APPROVED;
        $this->quote->save();

        if ($this->quote->customer->getSetting('auto_convert_quote')) {
            $invoice = (new ConvertQuote($this->quote, $invoice_repo))->run();
            $this->quote->invoice_id = $invoice->id;
            $this->quote->save();
        }

        if($this->quote->customer->getSetting('auto_email_quote')) {
            $this->sendEmail(null, trans('texts.quote_approved_subject'), trans('texts.quote_approved_body'));
        }

        event(new QuoteWasApproved($this->quote));

        if ($this->quote->customer->getSetting('auto_archive_quote')) {
            $quote_repo->archive($this->quote);
        }

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
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'quote')
    {
        return (new QuoteEmail($this->quote, $subject, $body, $template, $contact))->run();
    }

    public function calculateInvoiceTotals(): Quote
    {
        return $this->calculateTotals($this->quote);
    }
}
