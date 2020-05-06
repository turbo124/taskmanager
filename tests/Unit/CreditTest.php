<?php

namespace Tests\Unit;

use App\Account;
use App\Credit;
use App\Factory\CreditFactory;
use App\Filters\CreditFilter;
use App\Filters\InvoiceFilter;
use App\Repositories\CreditRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use App\Invoice;
use App\User;
use App\Customer;
use App\Repositories\InvoiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use App\Factory\InvoiceFactory;

/**
 * Description of InvoiceTest
 *
 * @author michael.hampton
 */
class CreditTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    private $customer;

    private $account;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = factory(Customer::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_can_show_all_the_credits()
    {
        factory(Credit::class)->create();
        $list = (new CreditFilter(new CreditRepository(new Credit)))->filter(new SearchRequest(), 1);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_credit()
    {
        $credit = factory(Credit::class)->create();
        $invoiceRepo = new CreditRepository($credit);
        $deleted = $invoiceRepo->newDelete($credit);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_credit()
    {
        $credit = factory(Credit::class)->create();
        $taskRepo = new CreditRepository($credit);
        $deleted = $taskRepo->archive($credit);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_credit()
    {
        $credit = factory(Credit::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $creditRepo = new CreditRepository($credit);
        $updated = $creditRepo->save($data, $credit);
        $found = $creditRepo->findCreditById($credit->id);
        $this->assertInstanceOf(Credit::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_credit()
    {
        $credit = factory(Credit::class)->create();
        $creditRepo = new CreditRepository(new Credit);
        $found = $creditRepo->findCreditById($credit->id);
        $this->assertInstanceOf(Credit::class, $found);
        $this->assertEquals($credit->customer_id, $found->customer_id);
    }

    /** @test */
    public function it_can_create_a_credit()
    {

        $customerId = $this->customer->id;
        $total = $this->faker->randomFloat();
        $user = factory(User::class)->create();
        $factory = (new CreditFactory)->create($this->account, $user, $this->customer);


        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $user->id,
            'customer_id' => $this->customer->id,
            'total'       => $this->faker->randomFloat()
        ];

        $creditRepo = new CreditRepository(new Credit);
        $credit = $creditRepo->save($data, $factory);
        $this->assertInstanceOf(Credit::class, $credit);
        $this->assertEquals($data['customer_id'], $credit->customer_id);
    }
}
