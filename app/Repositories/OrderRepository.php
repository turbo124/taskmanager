<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 22/12/2019
 * Time: 13:05
 */

namespace App\Repositories;

use App\Events\Order\OrderWasBackordered;
use App\Events\Order\OrderWasCreated;
use App\Events\Order\OrderWasUpdated;
use App\Filters\LengthAwarePaginator;
use App\Filters\OrderFilter;
use App\Models\Account;
use App\Models\Order;
use App\Models\Task;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection;

/**
 * Class OrderRepository
 * @package App\Repositories
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $this->model = $order;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     *
     * @return Order
     * @throws Exception
     */
    public function findOrderById(int $id): Order
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return LengthAwarePaginator|OrderFilter
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new OrderFilter($this))->filter($search_request, $account);
    }

    /**
     * @param array $data
     * @param Order $order
     */
    public function updateOrder(array $data, Order $order): ?Order
    {
        $order->fill($data);
        $order = $this->save($data, $order);

        event(new OrderWasUpdated($order));

        return $order;
    }

    /**
     * @param array $data
     * @param Order $order
     * @return Order|null
     */
    public function createOrder(array $data, Order $order): ?Order
    {
        $order->fill($data);

        if ($order->customer->getSetting('inventory_enabled') === true) {
            $order = $order->service()->fulfillOrder($this);

            /************** hold stock ***************************/
            // if the order hasnt been failed at this point then reserve stock
            if ($order->status_id !== Order::STATUS_ORDER_FAILED) {
                $order->service()->holdStock();
            }
        }

        // save the order
        $order = $this->save($data, $order);

        // send backorder notification if order has been backordered
        if ($order->status_id === Order::STATUS_BACKORDERED) {
            event(new OrderWasBackordered($order));
        }

        event(new OrderWasCreated($order));

        return $order;
    }

    /**
     * @param array $data
     * @param Order $order
     * @return Order
     */
    public function save(array $data, Order $order): Order
    {
        $order->fill($data);
        $order = $this->populateDefaults($order);
        $order = $order->service()->calculateInvoiceTotals();
        $order->setNumber();

        $order->save();

        $this->saveInvitations($order, 'order', $data);

        return $order->fresh();
    }

    public function getOrdersForTask(Task $task): Collection
    {
        return $this->model->where('task_id', '=', $task->id)->get();
    }

}
