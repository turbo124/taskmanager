<?php

namespace Tests\Unit;

use App\Console\Commands\SendRecurringInvoices;
use App\Factory\RecurringInvoiceFactory;
use App\Filters\RecurringInvoiceFilter;
use App\Jobs\Invoice\SendRecurringInvoice;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\RecurringInvoice;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use App\Repositories\RecurringInvoiceRepository;
use App\Requests\SearchRequest;
use App\Transformations\TaskTransformable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecurringInvoiceTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    /**
     * @var User|Collection|Model|mixed
     */
    private User $user;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Customer|Collection|Model|mixed
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

    public function test_send_recurring_invoice () {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now()->format('Y-m-d');
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->subDays(1);
        $recurring_invoice->end_date = Carbon::now()->addDays(15);
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();

        $this->assertEquals(Carbon::parse($updated_recurring_invoice->next_send_date)->format('Y-m-d'), Carbon::today()->addDays($recurring_invoice->frequency)->format('Y-m-d'));
        $this->assertEquals(Carbon::parse($updated_recurring_invoice->last_sent_date)->format('Y-m-d'), Carbon::today()->format('Y-m-d'));

        $invoice = Invoice::where('recurring_invoice_id', $recurring_invoice->id)->first();
        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public function test_send_recurring_invoice_fails () {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now()->format('Y-m-d');
        $recurring_invoice->last_sent_date = Carbon::now()->format('Y-m-d');
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->addDays(1);
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();
        $a = $recurring_invoice->next_send_date;
        $b = Carbon::parse($updated_recurring_invoice->next_send_date)->format('Y-m-d');

        $this->assertEquals($a, $b);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
