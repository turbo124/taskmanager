<?php

namespace App\Repositories;

use App\Filters\RecurringInvoiceFilter;
use App\Models\Account;
use App\Models\RecurringInvoice;
use App\Repositories\Base\BaseRepository;
use App\Requests\SearchRequest;
use App\Traits\BuildVariables;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * RecurringInvoiceRepository
 */
class RecurringInvoiceRepository extends BaseRepository
{
    use BuildVariables;

    /**
     * RecurringInvoiceRepository constructor.
     * @param RecurringInvoice $invoice
     */
    public function __construct(RecurringInvoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    /**
     * @param $data
     * @param RecurringInvoice $invoice
     * @return RecurringInvoice|null
     */
    public function save($data, RecurringInvoice $invoice): ?RecurringInvoice
    {
        $invoice->fill($data);
        $invoice = $this->populateDefaults($invoice);
        $invoice = $this->formatNotes($invoice);
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->setNumber();

        $invoice->save();

        $this->saveInvitations($invoice, 'recurringInvoice', $data, 'recurring_invoice');

        return $invoice->fresh();
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
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
