<?php

namespace Tests\Unit;

use App\Cases;
use App\Customer;
use App\Events\Lead\LeadWasCreated;
use App\Factory\CaseFactory;
use App\Factory\LeadFactory;
use App\Factory\RecurringInvoiceFactory;
use App\Filters\CaseFilter;
use App\Filters\LeadFilter;
use App\Filters\RecurringInvoiceFilter;
use App\Lead;
use App\RecurringInvoice;
use App\Repositories\CaseRepository;
use App\Repositories\LeadRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\RecurringInvoiceRepository;
use App\Requests\SearchRequest;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;
use App\User;
use App\Account;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\TaskTransformable;

class RecurringInvoiceTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    /**
     * @var User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private User $user;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Customer|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private Customer $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->customer = factory(Customer::class)->create();
    }

    /** @test */
    public function it_can_show_all_the_invoices()
    {
        factory(RecurringInvoice::class)->create();
        $list = (new RecurringInvoiceFilter(new RecurringInvoiceRepository(new RecurringInvoice())))->filter(
            new SearchRequest(),
            $this->account
        );
        $this->assertNotEmpty($list);
        // $this->assertInstanceOf(Collection::class, $list);
        //$this->assertEquals($insertedtask->title, $myLastElement['title']);
    }

    /** @test */
    public function it_can_delete_the_invoice()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $taskRepo = new RecurringInvoiceRepository($recurring_invoice);
        $deleted = $taskRepo->newDelete($recurring_invoice);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_recurring_invoice()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $taskRepo = new RecurringInvoiceRepository($recurring_invoice);
        $deleted = $taskRepo->archive($recurring_invoice);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_recurring_invoice()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $data = ['due_date' => Carbon::today()->addDays(5)];
        $recurringInvoiceRepo = new RecurringInvoiceRepository($recurring_invoice);
        $task = $recurringInvoiceRepo->save($data, $recurring_invoice);
        $found = $recurringInvoiceRepo->findInvoiceById($recurring_invoice->id);
        $this->assertInstanceOf(RecurringInvoice::class, $recurring_invoice);
        $this->assertEquals($data['due_date'], $found->due_date);
    }

    /** @test */
    public function it_can_show_the_invoice()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurringInvoiceRepo = new RecurringInvoiceRepository(new RecurringInvoice());
        $found = $recurringInvoiceRepo->findInvoiceById($recurring_invoice->id);
        $this->assertInstanceOf(RecurringInvoice::class, $found);
        $this->assertEquals($recurring_invoice->total, $found->total);
    }


    /** @test */
    public function it_can_create_a_invoice()
    {
        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $this->user->id,
            'customer_id' => $this->customer->id,
            'total'       => 200
        ];

        $recurringInvoiceRepo = new RecurringInvoiceRepository(new RecurringInvoice);
        $factory = (new RecurringInvoiceFactory)->create($this->customer, $this->account, $this->user, 200);
        $recurring_invoice = $recurringInvoiceRepo->save($data, $factory);

        $this->assertInstanceOf(RecurringInvoice::class, $recurring_invoice);
        $this->assertEquals($data['total'], $recurring_invoice->total);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
