<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 22/12/2019
 * Time: 13:05
 */

namespace App\Repositories;

use App\ClientContact;
use App\Factory\OrderInvitationFactory;
use App\NumberGenerator;
use App\Order;
use App\OrderInvitation;
use App\Product;
use App\Task;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Support\Collection;

class OrderRepository extends BaseRepository
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
     * @param $data
     * @param Order $order
     * @return Order
     */
    public function save($data, Order $order): Order
    {
        $order->fill($data);
        $order = $this->populateDefaults($order);
        $order = $order->service()->calculateInvoiceTotals();

        if(empty($order->number)) {
            $order->number = (new NumberGenerator)->getNextNumberForEntity($order->customer, $order);
        }
      
        $order->save();

        $this->saveInvitations($order, 'order', $data);

        return $order->fresh();
    }

    public function getInvitationByKey($key): ?OrderInvitation
    {
        return OrderInvitation::whereRaw("BINARY `key`= ?", [$key])->first();
    }

    public function getOrdersForTask(Task $task): Collection
    {
        return $this->model->where('task_id', '=', $task->id)->get();
    }

}
