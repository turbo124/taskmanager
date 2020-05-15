<?php

namespace Tests\Unit;

use App\ClientContact;
use App\Company;
use App\Customer;
use App\Account;
use App\Filters\CustomerFilter;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;
use App\Requests\SearchRequest;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\CustomerTransformable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use App\Brand;
use App\Factory\CustomerFactory;

class CustomerTest extends TestCase
{

    use DatabaseTransactions, CustomerTransformable, WithFaker;

    private $account;

    private $user;

    private $company;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->company = factory(Company::class)->create();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
    }

    /** @test */
    public function it_can_transform_the_customer()
    {
        $customer = factory(Customer::class)->create();
        $repo = new CustomerRepository($customer);
        $customerFromDb = $repo->findCustomerById($customer->id);
        $cust = $this->transformCustomer($customer);
        //$this->assertInternalType('string', $customerFromDb->status);
        $this->assertNotEmpty($cust);
    }

    /** @test */
    public function it_can_delete_the_customer()
    {
        $customer = factory(Customer::class)->create();
        $invoiceRepo = new CustomerRepository($customer);
        $deleted = $invoiceRepo->newDelete($customer);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_customer()
    {
        $customer = factory(Customer::class)->create();
        $taskRepo = new CustomerRepository($customer);
        $deleted = $taskRepo->archive($customer);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_fails_when_the_customer_is_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $customer = new CustomerRepository(new Customer);
        $customer->findCustomerById(999);
    }

    /** @test */
    public function it_can_find_a_customer()
    {
        $data = [
            'account_id' => $this->account->id,
            'name'       => $this->faker->firstName
        ];
        $customer = new CustomerRepository(new Customer);
        $factory = (new CustomerFactory())->create($this->account, $this->user);
        $created = $customer->save($data, $factory);
        $found = $customer->findCustomerById($created->id);
        $this->assertInstanceOf(Customer::class, $found);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_update_the_customer()
    {
        $cust = factory(Customer::class)->create();
        $customer = new CustomerRepository($cust);
        $update = [
            'name' => $this->faker->firstName,
        ];
        $updated = $customer->save($update, $cust);
        $this->assertInstanceOf(Customer::class, $updated);
        //$this->assertEquals($update['name'], $cust->name);
        $this->assertDatabaseHas('customers', $update);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        $factory = (new CustomerFactory())->create($this->account, $this->user);

        $data = [
            'account_id' => $this->account->id,
            'name'       => $this->faker->firstName,
            'company_id' => $this->company->id,
            'phone'      => $this->faker->phoneNumber
        ];

        $contacts = [];
        $contacts[0]['first_name'] = $this->faker->firstName;
        $contacts[0]['last_name'] = $this->faker->lastName;
        $contacts[0]['phone'] = $this->faker->phoneNumber;
        $contacts[0]['email'] = $this->faker->safeEmail;


        $customer = new CustomerRepository(new Customer);
        $created = $customer->save($data, $factory);
        $this->assertInstanceOf(Customer::class, $created);
        $this->assertEquals($data['name'], $created->name);
        $collection = collect($data)->except('password');
        $this->assertDatabaseHas('customers', $collection->all());

        $clients = (new ClientContactRepository(new ClientContact))->save($contacts, $created);
        $this->assertTrue($clients);
    }

    public function it_errors_creating_the_customer_when_required_fields_are_not_passed()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $task = new CustomerRepository(new Customer);
        $task->createCustomer([]);
    }

    /** @test */
    public function it_can_list_all_customers()
    {
        factory(Customer::class, 5)->create();
        $list = (new CustomerFilter(new CustomerRepository(new Customer,
        )))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
