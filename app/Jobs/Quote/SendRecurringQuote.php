<?php

namespace App\Jobs\Quote;

use App\Factory\RecurringQuoteToQuoteFactory;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\RecurringQuote;
use App\Repositories\QuoteRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRecurringQuote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var QuoteRepository
     */
    private QuoteRepository $quote_repo;

    /**
     * SendRecurringQuote constructor.
     * @param QuoteRepository $quote_repo
     */
    public function __construct(QuoteRepository $quote_repo)
    {
        $this->quote_repo = $quote_repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->processRecurringInvoices();
    }

    private function processRecurringInvoices()
    {
        $recurring_quotes = RecurringQuote::whereDate('next_send_date', '=', Carbon::today())
                                          ->whereDate('date', '!=', Carbon::today())
                                          ->get();

        foreach ($recurring_quotes as $recurring_quote) {
            if ($recurring_quote->start_date->gt(Carbon::now()) || Carbon::now()->gt(
                    $recurring_quote->end_date
                )) {
                continue;
            }

            $quote = RecurringQuoteToQuoteFactory::create($recurring_quote, $recurring_quote->customer);
            $quote = $this->quote_repo->save(['recurring_quote_id' => $recurring_quote->id], $quote);

            $quote->service()->sendEmail(null, trans('texts.quote_subject'), trans('texts.quote_body'));

            $recurring_quote->last_sent_date = Carbon::today();
            $recurring_quote->next_send_date = Carbon::today()->addDays($recurring_quote->frequency);
            $recurring_quote->save();
        }
    }
}
