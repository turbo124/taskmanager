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
            $this->status('subscriptions', $request->status);
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

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('subscriptions.target_url', 'like', '%' . $filter . '%');
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
}
