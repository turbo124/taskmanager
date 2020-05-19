<?php

namespace App\Filters;

use App\Account;
use App\Company;
use App\Credit;
use App\Promocode;
use App\Repositories\CompanyRepository;
use App\Repositories\CreditRepository;
use App\Repositories\PromocodeRepository;
use App\Requests\SearchRequest;
use App\Transformations\PromocodeTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class PromocodeFilter extends QueryFilter
{
    use PromocodeTransformable;

    private $promocode_repo;

    private $model;

    public function __construct(PromocodeRepository $promocode_repo)
    {
        $this->promocode_repo = $promocode_repo;
        $this->model = $promocode_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'expires_at' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

//        if ($request->has('status')) {
//            $this->filterStatus($request->status);
//        }

//        if ($request->filled('customer_id')) {
//            $this->query->whereCustomerId($request->customer_id);
//        }

//        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
//            $this->filterDates($request);
//        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $companies = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->promocode_repo->paginateArrayResults($companies, $recordsPerPage);
            return $paginatedResults;
        }

        return $companies;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('code', 'like', '%' . $filter . '%');
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $promocodes = $list->map(
            function (Promocode $promocode) {
                return $this->transformPromocodes($promocode);
            }
        )->all();

        return $promocodes;
    }

    /**
     * Filters the list based on the status
     * archived, active, deleted
     *
     * @param string filter
     * @return Illuminate\Database\Query\Builder
     */
    public function filterStatus($filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        $table = 'credits';
        $status_parameters = explode(',', $filter);

        $this->query->whereNull($table . '.id');

        if (in_array(parent::STATUS_ACTIVE, $status_parameters)) {
            $this->query->orWhereNull($table . '.deleted_at');
        }

        if (in_array(parent::STATUS_ARCHIVED, $status_parameters)) {
            $this->query->orWhere(
                function ($query) use ($table) {
                    $query->whereNotNull($table . '.deleted_at');
                }
            );

            $this->query->withTrashed();
        }

        if (in_array(parent::STATUS_DELETED, $status_parameters)) {
            $this->query->orWhere($table . '.is_deleted', '=', 1)->withTrashed();
        }

        if (in_array('draft', $status_parameters)) {
            $this->query->where('status_id', Credit::STATUS_DRAFT);
        }

        if (in_array('partial', $status_parameters)) {
            $this->query->where('status_id', Credit::STAUTS_PARTIAL);
        }

        if (in_array('applied', $status_parameters)) {
            $this->query->where('status_id', Credit::STATUS_APPLIED);
        }
    }

}
