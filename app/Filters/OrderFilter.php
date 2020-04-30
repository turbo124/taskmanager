<?php

namespace App\Filters;

use App\Order;
use App\Repositories\OrderRepository;
use App\Repositories\Support;
use App\Requests\SearchRequest;
use App\Task;
use App\Transformations\OrderTransformable;

class OrderFilter
{
    use OrderTransformable;
    private $orderRepository;

    private $query;

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
     * @param int $account_id
     * @return LengthAwarePaginator|static
     */
    public function filter(SearchRequest $request, int $account_id)
    {
        $recordsPerPage = !$request->per_page ? 0 : $request->per_page;
        $orderBy = !$request->column ? 'due_date' : $request->column;
        $orderDir = !$request->order ? 'asc' : $request->order;

        $this->query = $this->model->select('*');

        if ($request->filled('search_term')) {
            $this->query = $this->searchFilter($request->search_term);
        }

        if ($request->has('status')) {
            $this->filterStatus($request->status);
        }

        if ($request->filled('customer_id')) {
            $this->query->whereCustomerId($request->customer_id);
        }

        if ($request->input('start_date') <> '' && $request->input('end_date') <> '') {
            $this->filterDates($request);
        }

        $this->addAccount($account_id);

        $this->orderBy($orderBy, $orderDir);

        $orders = $this->transformList();

        if ($recordsPerPage > 0) {
            $paginatedResults = $this->orderRepository->paginateArrayResults($orders, $recordsPerPage);
            return $paginatedResults;
        }

        return $orders;
    }

    private function transformList()
    {
        $list = $this->query->get();

        $orders = $list->map(function (Order $order) {
            return $this->transformOrder($order);
        })->all();
        return $orders;
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

    private function addAccount(int $account_id)
    {
        $this->query->where('account_id', '=', $account_id);
    }

    private function orderBy($orderBy, $orderDir)
    {
        $this->query->orderBy($orderBy, $orderDir);
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
}
