<?php

namespace App\Repositories;

use App\ClientContact;
use App\Repositories\Base\BaseRepository;
use App\Quote;
use Exception;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\QuoteRepositoryInterface;
use Illuminate\Http\Request;
use App\Task;
use App\QuoteInvitation;
use App\Customer;
use App\NumberGenerator;

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
     * Sync the tasks
     *
     * @param array $params
     */
    /*public function syncTasks(int $task_id)
    {
        $this->model->tasks()->sync($task_id);
    } */

    /**
     * @param int $id
     *
     * @return Quote
     * @throws Exception
     */
    public function findQuoteById(int $id): Quote
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }

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

    public function getInvitationByKey($key): ?QuoteInvitation
    {
        return QuoteInvitation::whereRaw("BINARY `key`= ?", [$key])->first();
    }

    /**
     * List all the invoices
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listQuotes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     *
     * @param int $customerId
     * @return type
     */
    public function getQuoteForTask(Task $objTask): Quote
    {
        return $this->model->where('task_id', $objTask->id)->first();
    }

}
