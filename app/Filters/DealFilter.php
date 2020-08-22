<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Deal;
use App\Repositories\DealRepository;
use App\Requests\SearchRequest;
use App\Transformations\DealTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class DealFilter extends QueryFilter
{
    use DealTransformable;

    private $dealRepository;

    private $model;

    /**
     * DealFilter constructor.
     * @param DealRepository $dealRepository
     */
    public function __construct(DealRepository $dealRepository)
    {
        $this->dealRepository = $dealRepository;
        $this->model = $dealRepository->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'title' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query =
            $this->model->select('*');

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->filled('project_id')) {
            $this->query->whereProjectId($request->project_id);
        }

        if ($request->filled('task_status')) {
            $this->status('deals', $request->task_status);
        }

        if ($request->filled('task_type')) {
            $this->query->whereTaskType($request->task_type);
        }

        if ($request->filled('user_id')) {
            $this->query->where('assigned_to', '=', $request->user_id);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $deals = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->dealRepository->paginateArrayResults($deals, $recordsPerPage);
            return $paginatedResults;
        }

        return $deals;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('title', 'like', '%' . $filter . '%')->orWhere('content', 'like', '%' . $filter . '%')
                      ->orWhere('rating', 'like', '%' . $filter . '%')
                      ->orWhere('custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('custom_value4', 'like', '%' . $filter . '%');
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
        $deals = $list->map(
            function (Deal $deal) {
                return $this->transformDeal($deal);
            }
        )->all();

        return $deals;
    }
}
