<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\ClientContact;
use App\Events\Quote\QuoteWasCreated;
use App\Events\Quote\QuoteWasUpdated;
use App\Filters\QuoteFilter;
use App\Jobs\Order\QuoteOrders;
use App\Jobs\Product\UpdateProductPrices;
use App\Repositories\Base\BaseRepository;
use App\Models\Quote;
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\QuoteInvitation;
use App\Models\Customer;
use App\Models\NumberGenerator;

/**
 * Class QuoteRepository
 * @package App\Repositories
 */
class QuoteRepository extends BaseRepository implements QuoteRepositoryInterface
{

    /**
     * QuoteRepository constructor.
     *
     * @param Quote $quote
     */
    public function __construct(Quote $quote)
    {
        parent::__construct($quote);
        $this->model = $quote;
    }

    /**
     * @param int $id
     * @return \App\Models\Quote
     */
    public function findQuoteById(int $id): Quote
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param Quote $quote
     * @return Quote
     */
    public function createQuote(array $data, Quote $quote): ?Quote
    {
        $quote = $this->save($data, $quote);
        $quote->service()->createRecurringQuote($data);
        QuoteOrders::dispatchNow($quote);
        event(new QuoteWasCreated($quote));

        return $quote;
    }

    /**
     * @param array $data
     * @param Quote $quote
     * @return Quote|null
     */
    public function updateQuote(array $data, Quote $quote): ?Quote
    {
        $quote = $this->save($data, $quote);
        QuoteOrders::dispatchNow($quote);
        event(new QuoteWasUpdated($quote));

        return $quote;
    }

    /**
     * @param $data
     * @param Quote $quote
     * @return \App\Models\Quote|null
     */
    public function save($data, Quote $quote): ?Quote
    {
        $quote->fill($data);
        $quote = $this->populateDefaults($quote);
        $quote = $quote->service()->calculateInvoiceTotals();
        $quote->setNumber();

        $quote->save();

        $this->saveInvitations($quote, 'quote', $data);

        //if ($quote->customer->getSetting('should_update_products') === true) {
        UpdateProductPrices::dispatchNow($quote->line_items);
        //}

        return $quote->fresh();
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new QuoteFilter($this))->filter($search_request, $account);
    }

    /**
     * @param Task $objTask
     * @return \App\Models\Quote
     */
    public function getQuoteForTask(Task $objTask): Quote
    {
        return $this->model->where('task_id', $objTask->id)->first();
    }

}
