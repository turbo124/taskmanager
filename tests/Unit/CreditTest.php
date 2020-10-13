<?php

namespace Tests\Unit;

use App\Factory\CreditFactory;
use App\Models\Account;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use App\Repositories\CreditRepository;
use App\Requests\SearchRequest;
use App\Search\CreditSearch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $this->customer = Customer::factory()->create();
        $contact = CustomerContact::factory()->create(['customer_id' => $this->customer->id]);
        $this->customer->contacts()->save($contact);
        $this->account = Account::where('id', 1)->first();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_show_all_the_credits()
    {
        Credit::factory()->create();
        $list = (new CreditSearch(new CreditRepository(new Credit)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_credit()
    {
        $credit = Credit::factory()->create();
        $invoiceRepo = new CreditRepository($credit);
        $deleted = $invoiceRepo->newDelete($credit);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_credit()
    {
        $credit = Credit::factory()->create();
        $taskRepo = new CreditRepository($credit);
        $deleted = $taskRepo->archive($credit);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_credit()
    {
        $credit = Credit::factory()->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $creditRepo = new CreditRepository($credit);
        $updated = $creditRepo->updateCreditNote($data, $credit);
        $found = $creditRepo->findCreditById($credit->id);
        $this->assertInstanceOf(Credit::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_credit()
    {
        $credit = Credit::factory()->create();
        $creditRepo = new CreditRepository(new Credit);
        $found = $creditRepo->findCreditById($credit->id);
        $this->assertInstanceOf(Credit::class, $found);
        $this->assertEquals($credit->customer_id, $found->customer_id);
    }

    /** @test */
    public function it_can_create_a_credit()
    {
        $user = User::factory()->create();
        $factory = (new CreditFactory)->create($this->account, $user, $this->customer);

        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $user->id,
            'customer_id' => $this->customer->id,
            'total'       => $this->faker->randomFloat()
        ];

        $creditRepo = new CreditRepository(new Credit);
        $credit = $creditRepo->createCreditNote($data, $factory);
        $this->assertInstanceOf(Credit::class, $credit);
        $this->assertEquals($data['customer_id'], $credit->customer_id);
        $this->assertNotEmpty($credit->invitations);
    }
}
