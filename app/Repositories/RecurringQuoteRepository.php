<?php

namespace App\Repositories;

use App\Filters\RecurringQuoteFilter;
use App\Models\Account;
use App\Models\RecurringQuote;
use App\Repositories\Base\BaseRepository;
use App\Requests\SearchRequest;
use App\Traits\BuildVariables;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * RecurringQuoteRepository
 */
class RecurringQuoteRepository extends BaseRepository
{
    use BuildVariables;

    /**
     * RecurringQuoteRepository constructor.
     * @param RecurringQuote $quote
     */
    public function __construct(RecurringQuote $quote)
    {
        parent::__construct($quote);
        $this->model = $quote;
    }

    /**
     * @param $data
     * @param RecurringQuote $quote
     * @return RecurringQuote|null
     */
    public function save($data, RecurringQuote $quote): ?RecurringQuote
    {
        $quote->fill($data);
        $quote = $this->populateDefaults($quote);
        $quote = $this->formatNotes($quote);
        $quote = $quote->service()->calculateInvoiceTotals();
        $quote->setNumber();

        $quote->save();

        $this->saveInvitations($quote, $data);

        return $quote->fresh();
    }

    /**
     * @param int $id
     * @return RecurringQuote
     */
    public function findQuoteById(int $id): RecurringQuote
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new RecurringQuoteFilter($this))->filter($search_request, $account);
    }


    public function getModel()
    {
        return $this->model;
    }
}
