<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Company;
use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Requests\SearchRequest;
use App\Transformations\GroupTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupFilter extends QueryFilter
{
    use GroupTransformable;

    private $group_setting_repo;

    private $model;

    /**
     * GroupFilter constructor.
     * @param GroupRepository $group_setting_repo
     */
    public function __construct(GroupRepository $group_setting_repo)
    {
        $this->group_setting_repo = $group_setting_repo;
        $this->model = $group_setting_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'name' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->has('status')) {
            $this->status('groups', $request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $groups = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->group_setting_repo->paginateArrayResults($groups, $recordsPerPage);
            return $paginatedResults;
        }

        return $groups;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        $this->query->where('name', 'like', '%' . $filter . '%');
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $groups = $list->map(
            function (Group $group) {
                return $this->transformGroup($group);
            }
        )->all();

        return $groups;
    }

    /**
     * @param $filters
     * @param int $account_id
     * @return mixed
     */
    public function filterBySearchCriteria($filters, int $account_id)
    {
        $this->query = $this->model->select('group_settings.*');
        foreach ($filters as $column => $value) {
            if (empty($value)) {
                continue;
            }

            if ($column === 'status_id') {
                $this->status($value);
                continue;
            }
            $this->query->where($column, '=', $value);
        }

        $this->addAccount($account_id);

        return $this->transformList();
    }

}
