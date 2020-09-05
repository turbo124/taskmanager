<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\PurchaseOrder;
use App\Repositories\PurchaseOrderRepository;
use App\Requests\SearchRequest;
use App\Transformations\PurchaseOrderTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class PurchaseOrderFilter extends QueryFilter
{
    use PurchaseOrderTransformable;

    private $poRepository;

    private $model;

    /**
     * QuoteFilter constructor.
     * @param PurchaseOrderRepository $poRepository
     */
    public function __construct(PurchaseOrderRepository $poRepository)
    {
        $this->poRepository = $poRepository;
        $this->model = $poRepository->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param int $account_id
     * @return LengthAwarePaginator|mixed
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'due_date' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->has('status')) {
            $this->status('purchase_orders', $request->status);
        }

        if ($request->filled('company_id')) {
            $this->query->whereCompanyId($request->company_id);
        }

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $pos = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->poRepository->paginateArrayResults($pos, $recordsPerPage);
            return $paginatedResults;
        }

        return $pos;
    }

    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('purchase_orders.number', 'like', '%' . $filter . '%')
                      ->orWhere('purchase_orders.custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('purchase_orders.custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('purchase_orders.custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('purchase_orders.custom_value4', 'like', '%' . $filter . '%');
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
        $pos = $list->map(
            function (PurchaseOrder $po) {
                return $this->transformPurchaseOrder($po);
            }
        )->all();

        return $pos;
    }
}
