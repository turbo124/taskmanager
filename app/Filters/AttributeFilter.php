<?php

namespace App\Filters;

use App\Account;
use App\Attribute;
use App\Repositories\AttributeRepository;
use App\Repositories\SubscriptionRepository;
use App\Requests\SearchRequest;
use App\Subscription;
use App\Transformations\AttributeTransformable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * TokenFilters
 */
class AttributeFilter extends QueryFilter
{
    private $attribute_repo;

    private $model;

    /**
     * AttributeFilter constructor.
     * @param AttributeRepository $attribute_rep
     */
    public function __construct(AttributeRepository $attribute_repo)
    {
        $this->attribute_repo = $attribute_repo;
        $this->model = $attribute_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'name' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('attributes.*');

        if ($request->has('status')) {
            $this->status('attributes', $request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        //$this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $attributes = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->attribute_repo->paginateArrayResults($attributes, $recordsPerPage);
            return $paginatedResults;
        }

        return $attributes;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('subscriptions.name', 'like', '%' . $filter . '%');
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $attributes = $list->map(
            function (Attribute $attribute) {
                return (new AttributeTransformable)->transformAttribute($attribute);
            }
        )->all();

        return $attributes;
    }
}
