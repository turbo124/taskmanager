<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Brand;
use App\Repositories\BrandRepository;
use App\Requests\SearchRequest;
use App\Transformations\BrandTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BrandFilter
 * @package App\Filters
 */
class BrandFilter extends QueryFilter
{
    use BrandTransformable;

    /**
     * @var BrandRepository
     */
    private BrandRepository $brand_repo;

    private $model;

    /**
     * BrandFilter constructor.
     * @param BrandRepository $brand_repo
     */
    public function __construct(BrandRepository $brand_repo)
    {
        $this->brand_repo = $brand_repo;
        $this->model = $brand_repo->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'created_at' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('brands.*');

        if ($request->has('status')) {
            $this->status('brands', $request->status);
        }

        if ($request->has('search_term') && !empty($request->search_term)) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $brands = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->brand_repo->paginateArrayResults($brands, $recordsPerPage);
            return $paginatedResults;
        }

        return $brands;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        return $this->query->where('brands.name', 'like', '%' . $filter . '%');
    }

    /**
     * @param $list
     * @return mixed
     */
    private function transformList()
    {
        $list = $this->query->get();
        $brands = $list->map(
            function (Brand $brand) {
                return $this->transformBrand($brand);
            }
        )->all();

        return $brands;
    }
}
