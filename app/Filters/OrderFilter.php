<?php

namespace App\Filters;

use App\Models\Account;
use App\Models\Order;
use App\Models\Task;
use App\Repositories\OrderRepository;
use App\Repositories\Support;
use App\Requests\SearchRequest;
use App\Transformations\OrderTransformable;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderFilter extends QueryFilter
{
    use OrderTransformable;

    private $orderRepository;

    private $model;

    /**
     * OrderFilter constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->model = $orderRepository->getModel();
    }

    /**
     * @param SearchRequest $request
     * @param Account $account
     * @return LengthAwarePaginator
     */
    public function filter(SearchRequest $request, Account $account)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'due_date' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->has('status')) {
            $this->status('product_task', $request->status);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account);

        $this->orderBy($orderBy, $orderDir);

        $orders = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->orderRepository->paginateArrayResults($orders, $recordsPerPage);
            return $paginatedResults;
        }

        return $orders;
    }

    /**
     * Filter based on search text
     *
     * @param string query filter
     * @return Illuminate\Database\Query\Builder
     * @deprecated
     *
     */
    public function searchFilter(string $filter = '')
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }
        return $this->query->where(
            function ($query) use ($filter) {
                $query->where('invoices.number', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.po_number', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.date', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.total', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.balance', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.custom_value1', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.custom_value2', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.custom_value3', 'like', '%' . $filter . '%')
                      ->orWhere('product_task.custom_value4', 'like', '%' . $filter . '%');
            }
        );
    }

    private function transformList()
    {
        $list = $this->query->get();

        $orders = $list->map(
            function (Order $order) {
                return $this->transformOrder($order);
            }
        )->all();
        return $orders;
    }

    /**
     * @param Task $task
     * @return Support
     */
    public function filterByTask(Task $task)
    {
        $this->baseQuery();
        $this->addTaskToQuery($task);
        return $this->transformList();
    }

    private function baseQuery()
    {
        $this->query = $this->model->join('products', 'products.id', '=', 'product_task.product_id')
                                   ->select('product_task.*', 'products.price', 'product_task.id as order_id');
    }

    /**
     * @param Task $objTask
     */
    private function addTaskToQuery(Task $objTask)
    {
        $this->baseQuery();
        $this->query->where('product_task.task_id', $objTask->id);
    }

    /**
     * @param Task $objTask
     * @param int $status
     * @return mixed
     */
    public function getProductsForTask(Task $objTask, $status)
    {
        $this->baseQuery();
        $this->addTaskToQuery($objTask);
        $this->addStatusToQuery($status);

        return $this->transformList();
    }

    /**
     * @param $filter
     * @return mixed
     */
    private function addStatusToQuery($filter)
    {
        if (strlen($filter) == 0) {
            return $this->query;
        }

        $filters = explode(',', $filter);

        $this->query->whereIn('product_task.status', $filters);
    }
}
