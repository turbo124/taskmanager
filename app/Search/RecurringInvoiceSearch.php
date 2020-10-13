<?php

namespace App\Search;

use App\Models\Account;
use App\Models\RecurringInvoice;
use App\Repositories\RecurringInvoiceRepository;
use App\Requests\SearchRequest;
use App\Transformations\RecurringInvoiceTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class RecurringInvoiceSearch extends QueryFilter
{
    use RecurringInvoiceTransformable;

    private $recurringInvoiceRepository;

    private $model;

    /**
     * RecurringInvoiceSearch constructor.
     * @param RecurringInvoiceRepository $recurringInvoiceRepository
     */
    public function __construct(RecurringInvoiceRepository $recurringInvoiceRepository)
    {
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
        $this->model = $recurringInvoiceRepository->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'due_date' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->has('status')) {
            $this->status('recurring_invoices', $request->status);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $invoices = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->recurringInvoiceRepository->paginateArrayResults($invoices, $recordsPerPage);
            return $paginatedResults;
        }

        return $invoices;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('recurring_invoices.custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('recurring_invoices.custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('recurring_invoices.custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('recurring_invoices.custom_value4', 'like', '%' . $filter . '%');
            }
        );
    }

    private function transformList()
    {
        $list = $this->query->get();
        $invoices = $list->map(
            function (RecurringInvoice $invoice) {
                return $this->transformRecurringInvoice($invoice);
            }
        )->all();

        return $invoices;
    }
}