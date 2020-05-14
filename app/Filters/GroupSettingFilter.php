<?php

namespace App\Filters;

use App\Company;
use App\GroupSetting;
use App\Repositories\GroupSettingRepository;
use App\Requests\SearchRequest;
use App\Transformations\GroupSettingTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupSettingFilter extends QueryFilter
{
    use GroupSettingTransformable;

    private $group_setting_repo;

    private $model;

    /**
     * GroupSettingFilter constructor.
     * @param GroupSettingRepository $group_setting_repo
     */
    public function __construct(GroupSettingRepository $group_setting_repo)
    {
        $this->group_setting_repo = $group_setting_repo;
        $this->model = $group_setting_repo->getModel();
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

        if ($request->has('status')) {
            $this->status('group_settings', $request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account_id);

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
            function (GroupSetting $group) {
                return $this->transformGroupSetting($group);
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
