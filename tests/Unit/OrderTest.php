<?php

namespace Tests\Unit;

use App\Department;
use App\User;
use App\Repositories\DepartmentRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\DepartmentTransformable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use App\Services\Task\TaskService;
use App\Customer;
use App\Order;
use App\Task;
use App\Project;
use App\Account;
use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Factory\TaskFactory;
use App\ClientContact;
use App\Repositories\ClientContactRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ProjectRepository;

class OrderTest extends TestCase
{

    use DatabaseTransactions, DepartmentTransformable, WithFaker;

    private $account;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
    }

    /** @test */
    public function it_can_create_a_web_order()
    {
     $data = [
          'source_type' => 1,
    'title' => 'New web form request 2020/04/26',
    'task_type' => 3,
    'task_status' => 9,
    'products' => [
        0 => [
            'quantity' => 1,
                    'product_id' => 306,
                    'unit_price' => 12.99,
                    'unit_tax' => 17.5,
                    'unit_discount' => 0,
        ]
        ],

    'valued_at' => 12.99,
    '_token' => 'IUQkTOykrK1w98wFNjukdck6A4J0z0uERwOgGIBd',
    'first_name' => 'Lee',
    'last_name' => 'Jones',
    'email' => 'lee.jones@yahoo.com',
    'phone' => '01425 629322'
    ];

    $task = TaskFactory::create($this->user, $this->account);

    $task = (new TaskService($task))->createDeal((object) $data,
        (new CustomerRepository(new Customer, new ClientContactRepository(new ClientContact))),
        (new OrderRepository(new Order)),
        (new TaskRepository(new Task, new ProjectRepository(new Project))),
        true);

        $this->assertInstanceOf(Task::class, $task);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
