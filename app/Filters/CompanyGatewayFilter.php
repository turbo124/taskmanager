<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\CompanyGateway;
use App\Repositories\CompanyGatewayRepository;
use App\Repositories\InvoiceRepository;
use App\Requests\SearchRequest;
use App\Models\Invoice;
use App\Transformations\CompanyGatewayTransformable;
use App\Transformations\InvoiceTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyGatewayFilter extends QueryFilter
{
    use CompanyGatewayTransformable;

    private CompanyGatewayRepository $company_gateway_repo;

    private $model;

    /**
     * InvoiceFilter constructor.
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(CompanyGatewayRepository $company_gateway_repo)
    {
        $this->company_gateway_repo = $company_gateway_repo;
        $this->model = $company_gateway_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param \App\Models\Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'gateway_key' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->filled('status')) {
            $this->status('invoices', $request->status);
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
            $paginatedResults = $this->company_gateway_repo->paginateArrayResults($invoices, $recordsPerPage);

            return $paginatedResults;
        }

        return $invoices;
    }

    /**
     * Filter based on search text
     *
     * @param string query filter
     * @return Illuminate\Database\Query\Builder
     * @deprecated
     *
     */
    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('invoices.number', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.po_number', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.date', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.total', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.balance', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('invoices.custom_value4', 'like', '%' . $filter . '%');
            }
        );
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();

        $invoices = $list->map(
            function (CompanyGateway $invoice) {
                return $this->transformCompanyGateway($invoice);
            }
        )->all();

        return $invoices;
    }

}
