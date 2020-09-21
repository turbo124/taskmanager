<?php

namespace Tests\Unit;

use App\Factory\RecurringInvoiceFactory;
use App\Filters\RecurringInvoiceFilter;
use App\Jobs\Invoice\SendRecurringInvoice;
use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerContact;
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
        $contact = factory(CustomerContact::class)->create(['customer_id' => $this->customer->id]);
        $this->customer->contacts()->save($contact);
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
        $this->assertNotEmpty($recurring_invoice->invitations);
    }

    /** @test */
    public function test_date_ranges()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now();
        $recurring_invoice->customer_id = 5;
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->subDays(1);
        $recurring_invoice->end_date = Carbon::now()->addYears(1);
        $recurring_invoice->auto_billing_enabled = 0;
        $recurring_invoice->frequency = 30;
        $recurring_invoice->grace_period = 10;
        $recurring_invoice->cycles_remaining = 2;
        $recurring_invoice->save();

        $date_ranges = $recurring_invoice->calculateDateRanges();
        $this->assertEquals(13, count($date_ranges));
    }

    /** @test */
    public function test_send_recurring_invoice()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now();
        $recurring_invoice->customer_id = 5;
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->subDays(1);
        $recurring_invoice->end_date = Carbon::now()->addDays(15);
        $recurring_invoice->auto_billing_enabled = 0;
        $recurring_invoice->cycles_remaining = 2;
        $recurring_invoice->status_id = RecurringInvoice::STATUS_ACTIVE;
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();

        $this->assertTrue(
            $updated_recurring_invoice->next_send_date->eq(Carbon::today()->addDays($recurring_invoice->frequency))
        );
        $this->assertTrue($updated_recurring_invoice->last_sent_date->eq(Carbon::today()));
        $this->assertEquals(1, $updated_recurring_invoice->cycles_remaining);
        $invoice = Invoice::where('recurring_invoice_id', $recurring_invoice->id)->first();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);
    }

    /** @test */
    public function test_send_recurring_invoice_last_cycle()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now();
        $recurring_invoice->customer_id = 5;
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->subDays(1);
        $recurring_invoice->end_date = Carbon::now()->addDays(15);
        $recurring_invoice->auto_billing_enabled = 0;
        $recurring_invoice->cycles_remaining = 1;
        $recurring_invoice->status_id = RecurringInvoice::STATUS_ACTIVE;
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();

        $this->assertNull($updated_recurring_invoice->next_send_date);
        $this->assertTrue($updated_recurring_invoice->last_sent_date->eq(Carbon::today()));
        $this->assertEquals(0, $updated_recurring_invoice->cycles_remaining);
        $invoice = Invoice::where('recurring_invoice_id', $recurring_invoice->id)->first();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);
    }

    /** @test */
    public function test_send_recurring_invoice_endless()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now();
        $recurring_invoice->customer_id = 5;
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->subDays(1);
        $recurring_invoice->end_date = Carbon::now()->addDays(15);
        $recurring_invoice->auto_billing_enabled = 0;
        $recurring_invoice->cycles_remaining = 900;
        $recurring_invoice->frequency = 9000;
        $recurring_invoice->status_id = RecurringInvoice::STATUS_ACTIVE;
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();

        $this->assertTrue(
            $updated_recurring_invoice->next_send_date->eq(Carbon::today()->addDays($recurring_invoice->frequency))
        );
        $this->assertTrue($updated_recurring_invoice->last_sent_date->eq(Carbon::today()));
        $this->assertEquals(900, $updated_recurring_invoice->cycles_remaining); // cycles remianing should remain unchanged

        $invoice = Invoice::where('recurring_invoice_id', $recurring_invoice->id)->first();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);
    }

    /** @test */
    public function test_send_recurring_invoice_fails()
    {
        $recurring_invoice = factory(RecurringInvoice::class)->create();
        $recurring_invoice->next_send_date = Carbon::now();
        $recurring_invoice->last_sent_date = Carbon::now();
        $recurring_invoice->date = Carbon::now()->subDays(15);
        $recurring_invoice->start_date = Carbon::now()->addDays(1);
        $recurring_invoice->save();

        SendRecurringInvoice::dispatchNow(new InvoiceRepository(new Invoice()));

        $updated_recurring_invoice = $recurring_invoice->fresh();
        $a = $recurring_invoice->next_send_date;
        $b = $updated_recurring_invoice->next_send_date;

        $this->assertTrue($a->eq($b));
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
