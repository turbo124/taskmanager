<?php

namespace App\Filters;

use App\CompanyToken;
use App\Repositories\TokenRepository;
use App\Requests\SearchRequest;
use App\Transformations\CompanyTokenTransformable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * TokenFilters
 */
class TokenFilters extends QueryFilter
{
    use CompanyTokenTransformable;

    private $token_repo;

    private $model;

    /**
     * TokenFilters constructor.
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $token_repo)
    {
        $this->token_repo = $token_repo;
        $this->model = $token_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param int $account_id
     * @return mixed
     */
    public function filter(SearchRequest $request, int $account_id)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'name' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('company_tokens.*');

        if ($request->has('status')) {
            $this->status($request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account_id);

        $this->orderBy($orderBy, $orderDir);

        $tokens = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->token_repo->paginateArrayResults($tokens, $recordsPerPage);
            return $paginatedResults;
        }

        return $tokens;
    }

    private function filterDates($request)
    {
        $start = date("Y-m-d", strtotime($request->input('start_date')));
        $end = date("Y-m-d", strtotime($request->input('end_date')));
        $this->query->whereBetween('company_tokens.created_at', [$start, $end]);
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('company_tokens.name', 'like', '%' . $filter . '%');
    }

    private function orderBy($orderBy, $orderDir)
    {
        $this->query->orderBy($orderBy, $orderDir);
    }

    private function addAccount(int $account_id)
    {
        $this->query->where('company_tokens.account_id', '=', $account_id);
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $tokens = $list->map(
            function (CompanyToken $company_token) {
                return $this->transform($company_token);
            }
        )->all();

        return $tokens;
    }

    /**
     * Filters the list based on the status
     * archived, active, deleted
     *
     * @param string filter
     * @return Illuminate\Database\Query\Builder
     */
    public function status(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        $table = 'company_tokens';
        $filters = explode(',', $filter);

        $this->query->whereNull($table . '.id');
        if (in_array(parent::STATUS_ACTIVE, $filters)) {
            $this->query->orWhereNull($table . '.deleted_at');
        }

        if (in_array(parent::STATUS_ARCHIVED, $filters)) {
            $this->query->orWhere(
                function ($query) use ($table) {
                    $query->whereNotNull($table . '.deleted_at');
                }
            );

            $this->query->withTrashed();
        }
        if (in_array(parent::STATUS_DELETED, $filters)) {
            $this->query->orWhere($table . '.is_deleted', '=', 1)->withTrashed();
        }
    }

    /**
     * Sorts the list based on $sort
     *
     * @param string sort formatted as column|asc
     * @return Illuminate\Database\Query\Builder
     */
    public function sort(string $sort): Builder
    {
        $sort_col = explode("|", $sort);
        return $this->builder->orderBy($sort_col[0], $sort_col[1]);
    }
}
