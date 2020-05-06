<?php

namespace Tests\Unit;

use App\Invoice;
use App\User;
use App\Settings\AccountSettings;
use App\Repositories\InvoiceRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\DepartmentTransformable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use App\Services\Task\TaskService;
use App\Customer;
use App\Order;
use App\NumberGenerator;
use App\Task;
use App\Project;
use App\Account;
use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Factory\TaskFactory;
use App\Factory\OrderFactory;
use App\Product;
use App\ClientContact;
use App\Repositories\ClientContactRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ProjectRepository;
use App\Filters\OrderFilter;

class OrderTest extends TestCase
{

    use DatabaseTransactions, DepartmentTransformable, WithFaker;

    private $account;

    private $customer;

    private $user;

    private $product;

    private $objNumberGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->customer = factory(Customer::class)->create();
        $this->product = factory(Product::class)->create();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_orders()
    {
        factory(Order::class)->create();
        $list = (new OrderFilter(new OrderRepository(new Order)))->filter(new SearchRequest(), 1);
        $this->assertNotEmpty($list);
    }


    /** @test */
    public function it_can_update_the_order()
    {
        $order = factory(Order::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $orderRepo = new OrderRepository($order);
        $updated = $orderRepo->save($data, $order);
        $found = $orderRepo->findOrderById($order->id);
        $this->assertInstanceOf(Order::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_order()
    {
        $order = factory(Order::class)->create();
        $orderRepo = new OrderRepository(new Order);
        $found = $orderRepo->findOrderById($order->id);
        $this->assertInstanceOf(Order::class, $found);
        $this->assertEquals($order->customer_id, $found->customer_id);
    }

    /** @test */
    public function it_can_create_a_invoice()
    {

        $customerId = $this->customer->id;

        $total = $this->faker->randomFloat();
        $user = factory(User::class)->create();
        $factory = (new OrderFactory())->create($this->account, $user, $this->customer);

        $total = $this->faker->randomFloat();

        $data = [
            'account_id'     => $this->account->id,
            'user_id'        => $user->id,
            'customer_id'    => $this->customer->id,
            'total'          => $total,
            'balance'        => $total,
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $orderRepo = new OrderRepository(new Order);
        $order = $orderRepo->save($data, $factory);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($data['customer_id'], $order->customer_id);
    }

    /**
     * @codeCoverageIgnore
     */
    public function it_errors_creating_the_order_when_required_fields_are_not_passed()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $order = new OrderRepository(new Order);
        $order->createOrder([]);
    }

    /** @test */
    public function it_errors_finding_a_order()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $order = new OrderRepository(new Order);
        $order->findOrderById(999);
    }

    /** @test */
    public function it_can_delete_the_order()
    {
        $order = factory(Order::class)->create();
        $orderRepo = new OrderRepository(new Order);
        $deleted = $orderRepo->newDelete($order);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_order()
    {
        $order = factory(Order::class)->create();
        $orderRepo = new OrderRepository($order);
        $deleted = $orderRepo->archive($order);
        $this->assertTrue($deleted);
    }

    public function testOrderPadding()
    {
        $customer = factory(Customer::class)->create();
        $customerSettings = (new AccountSettings)->getAccountDefaults();
        $customerSettings->counter_padding = 5;
        $customerSettings->order_number_counter = 7;
        $customerSettings->order_number_pattern = '{$clientCounter}';
        $customer->settings = $customerSettings;
        $customer->save();

        $order = OrderFactory::create($this->account, $this->user, $customer);

        $order_number = $this->objNumberGenerator->getNextNumberForEntity($customer, $order);
        $this->assertEquals($customer->getSetting('counter_padding'), 5);
        $this->assertEquals($order_number, '00007');
        $this->assertEquals(strlen($order_number), 5);
    }

    /** @test */
    public function it_can_create_a_web_order()
    {
        $data = [
            'source_type' => 1,
            'title'       => 'New web form request 2020/04/26',
            'task_type'   => 3,
            'task_status' => 9,
            'products'    => [
                0 => [
                    'quantity'      => 1,
                    'product_id'    => $this->product->id,
                    'unit_price'    => 12.99,
                    'unit_tax'      => 17.5,
                    'unit_discount' => 0,
                ]
            ],

            'valued_at'  => 12.99,
            '_token'     => 'IUQkTOykrK1w98wFNjukdck6A4J0z0uERwOgGIBd',
            'first_name' => 'Lee',
            'last_name'  => 'Jones',
            'email'      => 'lee.jones@yahoo.com',
            'phone'      => '01425 629322'
        ];

        $task = TaskFactory::create($this->user, $this->account);

        $task = (new TaskService($task))->createDeal((object)$data,
            (new CustomerRepository(new Customer)),
            (new OrderRepository(new Order)),
            (new TaskRepository(new Task, new ProjectRepository(new Project))),
            true);

        $this->assertInstanceOf(Task::class, $task);
    }

    public function testOrderDispatch()
    {
        $order = factory(Order::class)->create();

        $account = $order->account;
        $settings = $account->settings;
        $settings->should_convert_order = true;
        $settings->should_email_order = true;
        $settings->should_archive_order = true;
        $account->settings = $settings;
        $account->save();

        $order = $order->service()->dispatch(new InvoiceRepository(new Invoice), new OrderRepository(new Order));
        $this->assertInstanceOf(Order::class, $order);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
