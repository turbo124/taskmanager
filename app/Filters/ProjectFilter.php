<?php

namespace App\Filters;

use App\Account;
use App\Project;
use App\Repositories\ProjectRepository;
use App\Transformations\ProjectTransformable;
use Illuminate\Http\Request;

class ProjectFilter extends QueryFilter
{
    use ProjectTransformable;

    private $projectRepository;
    private $model;

    /**
     * ProjectFilter constructor.
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->model = $projectRepository->getModel();
    }

    /**
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function filter(Request $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'title' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->has('status')) {
            $this->status('projects', $request->status);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $projects = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->projectRepository->paginateArrayResults($projects, $recordsPerPage);
            return $paginatedResults;
        }

        return $projects;
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $projects = $list->map(
            function (Project $project) {
                return $this->transformProject($project);
            }
        )->all();

        return $projects;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('projects.title', 'like', '%' . $filter . '%');
            }
        );
    }
}
