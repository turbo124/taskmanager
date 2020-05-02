<?php

namespace App\Filters;

use App\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\TaxRateRepository;
use App\Requests\SearchRequest;
use App\TaxRate;
use App\Transformations\TaxRateTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class TaxRateFilter extends QueryFilter
{
    use TaxRateTransformable;

    private $tax_rate_repo;

    private $model;

    /**
     * CompanyFilter constructor.
     * @param CompanyRepository $companyRepository
     */
    public function __construct(TaxRateRepository $tax_rate_repo)
    {
        $this->tax_rate_repo = $tax_rate_repo;
        $this->model = $tax_rate_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param int $account_id
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, int $account_id)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'name' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->has('status')) {
            $this->status('tax_rates', $request->status);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account_id);

        $this->orderBy($orderBy, $orderDir);

        $tax_rates = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->tax_rate_repo->paginateArrayResults($tax_rates, $recordsPerPage);
            return $paginatedResults;
        }

        return $tax_rates;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(function ($query) use ($filter) {
            $query->where('name', 'like', '%' . $filter . '%');
        });
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $companies = $list->map(function (TaxRate $tax_rate) {
            return $this->transformTaxRate($tax_rate);
        })->all();

        return $companies;
    }

}
