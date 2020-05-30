<?php

namespace App\Services\Task;

use App\Address;
use App\ClientContact;
use App\Events\Deal\DealWasCreated;
use App\Order;
use App\Task;
use Illuminate\Support\Facades\DB;
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
    /**
     * @var Task
     */
    private $task;
    private $request;

    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customer_repo;

    /**
     * @var OrderRepository
     */
    private OrderRepository $order_repo;

    /**
     * @var TaskRepository
     */
    private TaskRepository $task_repo;

    /**
     * @var bool
     */
    private bool $is_deal;

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
        Task $task,
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

    public function execute()
    {
        DB::beginTransaction();

        try {
            $customer = $this->saveCustomer();

            if (!$customer) {
                return null;
            }

            if (!$this->saveAddresses($customer)) {
                return null;
            }

            $this->task = $this->saveTask($customer);

            if (!$this->task) {
                return null;
            }

            if (!empty($this->request->products)) {
                $order = $this->saveOrder($customer);

                if (!$order) {
                    return null;
                }
            }

            DB::commit();
            return $this->task;
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    private function saveTask(Customer $customer): ?Task
    {
        try {
            $date = new DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $due_date = $date->format('Y-m-d');

            $this->task = $this->task_repo->save(
                [
                    'due_date'    => $due_date,
                    'created_by'  => $this->task->user_id,
                    'source_type' => $this->request->source_type,
                    'title'       => $this->request->title,
                    'description' => isset($this->request->description) ? $this->request->description : '',
                    'customer_id' => $customer->id,
                    'valued_at'   => $this->request->valued_at,
                    'task_type'   => $this->is_deal === true ? 3 : 2,
                    'task_status' => $this->request->task_status
                ],
                $this->task
            );

            if (!empty($this->request->contributors)) {
                $this->task->users()->sync($this->request->input('contributors'));
            }

            event(new DealWasCreated($this->task, $this->task->account));

            return $this->task;
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    private function saveCustomer(): ?Customer
    {
        $customer = CustomerFactory::create($this->task->account, $this->task->user);

        try {
            $contact = ClientContact::where('email', '=', $this->request->email)->first();

            if (!empty($contact)) {
                $contact->update(
                    [
                        'first_name' => $this->request->first_name,
                        'last_name'  => $this->request->last_name,
                        'email'      => $this->request->email,
                        'phone'      => $this->request->phone,
                    ]
                );

                $customer = $contact->customer;
            }

            $customer = $this->customer_repo->save(
                [
                    'name'                   => $this->request->first_name . ' ' . $this->request->last_name,
                    'phone'                  => $this->request->phone,
                    'website'                => isset($this->request->website) ? $this->request->website : '',
                    'currency_id'            => 2,
                    'default_payment_method' => 1
                ],
                $customer
            );

            if (empty($contact)) {
                $contacts [] = [
                    'first_name' => $this->request->first_name,
                    'last_name'  => $this->request->last_name,
                    'email'      => $this->request->email,
                    'phone'      => $this->request->phone,
                ];

                (new ClientContactRepository(new ClientContact))->save($contacts, $customer);
            }

            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    /**
     * @param Customer $customer
     * @return bool
     */
    private function saveAddresses(Customer $customer): bool
    {
        try {
            if (!empty($this->request->billing)) {
                Address::updateOrCreate(
                    ['customer_id' => $customer->id, 'address_type' => 1],
                    [
                        'address_1'    => $this->request->billing['address_1'],
                        'address_2'    => $this->request->billing['address_2'],
                        'zip'          => $this->request->billing['zip'],
                        'country_id'   => isset($this->request->billing['country_id']) ? $this->request->billing['country_id'] : 225,
                        'address_type' => 1
                    ]
                );
            }

            if (!empty($this->request->shipping)) {
                $address = Address::updateOrCreate(
                    ['customer_id' => $customer->id, 'address_type' => 2],
                    [
                        'address_1'    => $this->request->shipping['address_1'],
                        'address_2'    => $this->request->shipping['address_2'],
                        'zip'          => $this->request->shipping['zip'],
                        'country_id'   => isset($this->request->shipping['country_id']) ? $this->request->shipping['country_id'] : 225,
                        'address_type' => 2
                    ]
                );
            }

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @param Customer $customer
     * @return Order|null
     */
    private function saveOrder(Customer $customer): ?Order
    {
        try {
            $contacts = $customer->contacts->toArray();
            $invitations = [];

            foreach ($contacts as $contact) {
                $invitations[] = [
                    'client_contact_id' => $contact['id']
                ];
            }

            $order = OrderFactory::create($this->task->account, $this->task->user, $customer);

            $order = $this->order_repo->createOrder(
                [
                    'custom_surcharge1' => isset($this->request->shipping_cost) ? $this->request->shipping_cost : 0,
                    'invitations'       => $invitations,
                    'balance'           => $this->request->total,
                    'sub_total'         => $this->request->sub_total,
                    'total'             => $this->request->total,
                    //'tax_total'         => isset($this->request->tax_total) ? $this->request->tax_total : 0,
                    'discount_total'    => isset($this->request->discount_total) ? $this->request->discount_total : 0,
                    'tax_rate'          => isset($this->request->tax_rate) ? (float)str_replace(
                        '%',
                        '',
                        $this->request->tax_rate
                    ) : 0,
                    'line_items'        => $this->request->products,
                    'task_id'           => $this->task->id,
                    'date'              => date('Y-m-d')
                ],
                $order
            );

            $subject = $order->customer->getSetting('email_subject_order_received');
            $body = $order->customer->getSetting('email_template_order_received');

            $order->service()->sendEmail(null, $subject, $body);

            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }
}
