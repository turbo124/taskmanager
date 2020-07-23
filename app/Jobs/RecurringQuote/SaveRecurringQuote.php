<?php

namespace App\Jobs\RecurringQuote;

use App\Models\Account;
use App\Factory\QuoteToRecurringQuoteFactory;
use App\Models\Quote;
use App\Repositories\RecurringQuoteRepository;
use App\Models\RecurringQuote;
use App\Factory\RecurringQuoteFactory;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SaveRecurringQuote
{
    use Dispatchable;

    /**
     * @var array
     */
    private array $request;

    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * SaveRecurringQuote constructor.
     * @param array $request
     * @param \App\Models\Quote $quote
     */
    public function __construct(array $request, Quote $quote)
    {
        $this->request = $request;
        $this->quote = $quote;
    }

    /**
     *
     */
    public function handle(): ?RecurringQuote
    {
        if (!empty($this->request['recurring'])) {
            $recurring = json_decode($this->request['recurring'], true);
            $arrRecurring['start_date'] = $recurring['start_date'];
            $arrRecurring['end_date'] = $recurring['end_date'];
            $arrRecurring['frequency'] = $recurring['frequency'];
            $arrRecurring['recurring_due_date'] = $recurring['recurring_due_date'];
            $recurringQuote = (new RecurringQuoteRepository(new RecurringQuote))->save(
                $arrRecurring,
                QuoteToRecurringQuoteFactory::create($this->quote)
            );

            return $recurringQuote;
        }

        return null;
    }
}
