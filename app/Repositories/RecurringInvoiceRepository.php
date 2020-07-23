<?php

namespace App\Repositories;

use App\Models\Account;
use App\Filters\RecurringInvoiceFilter;
use App\Models\NumberGenerator;
use App\Repositories\Base\BaseRepository;
use App\Models\RecurringInvoice;
use App\Requests\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

/**
 * RecurringInvoiceRepository
 */
class RecurringInvoiceRepository extends BaseRepository
{
    /**
     * RecurringInvoiceRepository constructor.
     * @param \App\Models\RecurringInvoice $invoice
     */
    public function __construct(RecurringInvoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    /**
     * @param $data
     * @param RecurringInvoice $invoice
     * @return \App\Models\RecurringInvoice|null
     */
    public function save($data, RecurringInvoice $invoice): ?RecurringInvoice
    {
        $invoice->fill($data);
        //$invoice = $this->populateDefaults($invoice);
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->setNumber();

        $invoice->save();

        return $invoice->fresh();
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new RecurringInvoiceFilter($this))->filter($search_request, $account);
    }

    /**
     * @param int $id
     * @return RecurringInvoice
     */
    public function findInvoiceById(int $id): RecurringInvoice
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }
}
