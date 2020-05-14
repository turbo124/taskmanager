<?php

namespace App\Filters;

use App\Company;
use App\Design;
use App\GroupSetting;
use App\Repositories\DesignRepository;
use App\Repositories\GroupSettingRepository;
use App\Requests\SearchRequest;
use App\Transformations\GroupSettingTransformable;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Transformations\DesignTransformable;

class DesignFilter extends QueryFilter
{
    use DesignTransformable;

    private $design_repo;

    private $model;

    /**
     * GroupSettingFilter constructor.
     * @param GroupSettingRepository $group_setting_repo
     */
    public function __construct(DesignRepository $design_repo)
    {
        $this->design_repo = $design_repo;
        $this->model = $design_repo->getModel();
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
            $this->status('designs', $request->status);
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
            $paginatedResults = $this->design_repo->paginateArrayResults($groups, $recordsPerPage);
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
        $designs = $list->map(
            function (Design $design) {
                return $this->transformDesign($design);
            }
        )->all();

        return $designs;
    }

}
