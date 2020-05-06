<?php

namespace App\Services\Task;

use App\ClientContact;
use Illuminate\Support\Facades\Log;
use App\Customer;
use App\Factory\ClientContactFactory;
use App\Factory\CustomerFactory;
use App\Factory\OrderFactory;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TaskRepository;
use DateInterval;
use App\Events\Order\OrderWasCreated;
use DateTime;
use Illuminate\Http\Request;

/**
 * Class CreateDeal
 * @package App\Services\Task
 */
class CreateDeal
{
    private $task;
    private $request;
    private $customer_repo;
    private $order_repo;
    private $task_repo;
    private $is_deal;

    /**
     * CreateDeal constructor.
     * @param $task
     * @param Request $request
     * @param CustomerRepository $customer_repo
     * @param OrderRepository $order_repo
     * @param TaskRepository $task_repo
     * @param $is_deal
     */
    public function __construct(
        $task,
        $request,
        CustomerRepository $customer_repo,
        OrderRepository $order_repo,
        TaskRepository $task_repo,
        $is_deal
    ) {
        $this->task = $task;
        $this->request = $request;
        $this->customer_repo = $customer_repo;
        $this->order_repo = $order_repo;
        $this->task_repo = $task_repo;
        $this->is_deal = $is_deal;
    }

    public function run()
    {
        $factory = CustomerFactory::create($this->task->account, $this->task->user);

        $date = new DateTime(); // Y-m-d
        $date->add(new DateInterval('P30D'));
        $due_date = $date->format('Y-m-d');

        $contacts [] = [
            'first_name' => $this->request->first_name,
            'last_name'  => $this->request->last_name,
            'email'      => $this->request->email,
            'phone'      => $this->request->phone,
        ];

        $customer = $this->customer_repo->save([
            'name'                   => $this->request->first_name . ' ' . $this->request->last_name,
            'phone'                  => $this->request->phone,
            'website'                => isset($this->request->website) ? $this->request->website : '',
            'currency_id'            => 1,
            'default_payment_method' => 1
        ], $factory);

        (new ClientContactRepository(new ClientContact))->save($contacts, $customer);

        $this->task = $this->task_repo->save([
            'due_date'    => $due_date,
            'created_by'  => $this->task->user_id,
            'source_type' => $this->request->source_type,
            'title'       => $this->request->title,
            'description' => isset($this->request->description) ? $this->request->description : '',
            'customer_id' => $customer->id,
            'valued_at'   => $this->request->valued_at,
            'task_type'   => $this->is_deal === true ? 3 : 2,
            'task_status' => $this->request->task_status
        ], $this->task);

        if (!empty($this->request->contributors)) {
            $this->task->users()->sync($this->request->input('contributors'));
        }

        if (!empty($this->request->products)) {
            $this->saveOrder($customer);
        }

        return $this->task;
    }

    private function saveOrder(Customer $customer)
    {
        $total = 0;
        $tax_total = 0;
        $discount_total = 0;
        $sub_total = 0;

        foreach ($this->request->products as $product) {
            $total += $product['unit_price'] * $product['quantity'];

            if (isset($product['unit_tax']) && $product['unit_tax'] > 0) {
                $tax_total += ($total / 100) * $product['unit_tax'];
            }

            if (isset($product['unit_discount']) && $product['unit_discount'] > 0) {
                $discount_total += $product['unit_discount'];
            }
        }

        $sub_total = $total;

        if ($tax_total > 0) {
            $total += $tax_total;
        }

        $contacts = $customer->contacts->toArray();
        $invitations = [];

        foreach ($contacts as $contact) {
            $invitations[] = [
                'client_contact_id' => $contact['id']
            ];
        }

        $order = OrderFactory::create($this->task->account, $this->task->user, $customer);

        $order = $this->order_repo->save(
            [
                'invitations'    => $invitations,
                'balance'        => $total,
                'sub_total'      => $sub_total,
                'total'          => $total,
                'discount_total' => $discount_total,
                'tax_total'      => $tax_total,
                'line_items'     => $this->request->products,
                'task_id'        => $this->task->id,
                'date'           => date('Y-m-d')
            ], $order);


        $subject = $order->customer->getSetting('email_subject_order');
        $body = $order->customer->getSetting('email_template_order');
        event(new OrderWasCreated($order));
        $order->service()->sendEmail(null, $subject, $body);
    }
}
