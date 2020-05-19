<?php

namespace App\Repositories;

use App\Account;
use App\ClientContact;
use App\Filters\QuoteFilter;
use App\Repositories\Base\BaseRepository;
use App\Quote;
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use Illuminate\Http\Request;
use App\Task;
use App\QuoteInvitation;
use App\Customer;
use App\NumberGenerator;

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
     * @return Quote
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
     * @param $data
     * @param Quote $quote
     * @return Quote|null
     */
    public function save($data, Quote $quote): ?Quote
    {
        $quote->fill($data);
        $quote = $this->populateDefaults($quote);
        $quote = $quote->service()->calculateInvoiceTotals();
        $quote->setNumber();

        $quote->save();

        $this->saveInvitations($quote, 'quote', $data);

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
     * @return Quote
     */
    public function getQuoteForTask(Task $objTask): Quote
    {
        return $this->model->where('task_id', $objTask->id)->first();
    }

}
