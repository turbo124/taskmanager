<?php

namespace App\Filters;

use App\Repositories\SubscriptionRepository;
use App\Requests\SearchRequest;
use App\Subscription;
use App\Transformations\SubscriptionTransformable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * TokenFilters
 */
class SubscriptionFilters extends QueryFilter
{
    use SubscriptionTransformable;

    private $subscription_repo;

    private $model;

    /**
     * SubscriptionFilters constructor.
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(SubscriptionRepository $subscription_repo)
    {
        $this->subscription_repo = $subscription_repo;
        $this->model = $subscription_repo->getModel();
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

        $this->query = $this->model->select('subscriptions.*');

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

        $subscriptions = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->subscription_repo->paginateArrayResults($subscriptions, $recordsPerPage);
            return $paginatedResults;
        }

        return $subscriptions;
    }

    private function filterDates($request)
    {
        $start = date("Y-m-d", strtotime($request->input('start_date')));
        $end = date("Y-m-d", strtotime($request->input('end_date')));
        $this->query->whereBetween('subscriptions.created_at', [$start, $end]);
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('subscriptions.target_url', 'like', '%' . $filter . '%');
    }

    private function orderBy($orderBy, $orderDir)
    {
        $this->query->orderBy($orderBy, $orderDir);
    }

    private function addAccount(int $account_id)
    {
        $this->query->where('subscriptions.account_id', '=', $account_id);
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $subscriptions = $list->map(function (Subscription $subscription) {
            return $this->transform($subscription);
        })->all();

        return $subscriptions;
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
        $table = 'subscriptions';
        $filters = explode(',', $filter);

        $this->query->whereNull($table . '.id');
        if (in_array(parent::STATUS_ACTIVE, $filters)) {
            $this->query->orWhereNull($table . '.deleted_at');
        }

        if (in_array(parent::STATUS_ARCHIVED, $filters)) {
            $this->query->orWhere(function ($query) use ($table) {
                $query->whereNotNull($table . '.deleted_at');
            });

            $this->query->withTrashed();
        }
        if (in_array(parent::STATUS_DELETED, $filters)) {
            $this->query->orWhere($table . '.is_deleted', '=', 1)->withTrashed();
        }
    }
}
