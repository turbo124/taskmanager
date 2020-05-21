<?php

namespace App\Services\Quote;

use App\Factory\CloneQuoteToInvoiceFactory;
use App\Invoice;
use App\Quote;
use App\Repositories\InvoiceRepository;
use App\Repositories\QuoteRepository;

/**
 * Class ConvertQuote
 * @package App\Services\Quote
 */
class ConvertQuote
{
    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice_repo;

    /**
     * ConvertQuote constructor.
     * @param Quote $quote
     * @param InvoiceRepository $invoice_repo
     */
    public function __construct(Quote $quote, InvoiceRepository $invoice_repo)
    {
        $this->quote = $quote;
        $this->invoice_repo = $invoice_repo;
    }

    /**
     * @param $quote
     * @return mixed
     */
    public function run()
    {
        $invoice = CloneQuoteToInvoiceFactory::create(
            $this->quote,
            $this->quote->user,
            $this->quote->account
        );

        $invoice = $this->invoice_repo->save(
            [
                'status_id' => Invoice::STATUS_SENT
            ],
            $invoice
        );

        $this->invoice_repo->markSent($invoice);

        return $invoice;
    }
}
