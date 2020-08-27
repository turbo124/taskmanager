<?php

namespace Tests\Unit;

use App\Factory\RecurringQuoteFactory;
use App\Filters\RecurringQuoteFilter;
use App\Jobs\Invoice\SendRecurringInvoice;
use App\Jobs\Quote\SendRecurringQuote;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\RecurringInvoice;
use App\Models\RecurringQuote;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\RecurringQuoteRepository;
use App\Requests\SearchRequest;
use App\Transformations\TaskTransformable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecurringQuoteTest extends TestCase
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
    public function it_can_show_all_the_quotes()
    {
        factory(RecurringQuote::class)->create();
        $list = (new RecurringQuoteFilter(new RecurringQuoteRepository(new RecurringQuote())))->filter(
            new SearchRequest(),
            $this->account
        );
        $this->assertNotEmpty($list);
        // $this->assertInstanceOf(Collection::class, $list);
        //$this->assertEquals($insertedtask->title, $myLastElement['title']);
    }

    /** @test */
    public function it_can_delete_the_quote()
    {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $taskRepo = new RecurringQuoteRepository($recurring_quote);
        $deleted = $taskRepo->newDelete($recurring_quote);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_recurring_quote()
    {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $taskRepo = new RecurringQuoteRepository($recurring_quote);
        $deleted = $taskRepo->archive($recurring_quote);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_recurring_quote()
    {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $data = ['due_date' => Carbon::today()->addDays(5)];
        $recurringQuoteRepo = new RecurringQuoteRepository($recurring_quote);
        $task = $recurringQuoteRepo->save($data, $recurring_quote);
        $found = $recurringQuoteRepo->findQuoteById($recurring_quote->id);
        $this->assertInstanceOf(RecurringQuote::class, $recurring_quote);
        $this->assertEquals($data['due_date'], $found->due_date);
    }

    /** @test */
    public function it_can_show_the_quote()
    {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $recurringQuoteRepo = new RecurringQuoteRepository(new RecurringQuote());
        $found = $recurringQuoteRepo->findQuoteById($recurring_quote->id);
        $this->assertInstanceOf(RecurringQuote::class, $found);
        $this->assertEquals($recurring_quote->due_date, $found->due_date);
    }


    /** @test */
    public function it_can_create_a_quote()
    {
        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $this->user->id,
            'customer_id' => $this->customer->id,
            'total'       => 200
        ];

        $recurringQuoteRepo = new RecurringQuoteRepository(new RecurringQuote());
        $factory = (new RecurringQuoteFactory())->create($this->customer, $this->account, $this->user, 200);
        $recurring_quote = $recurringQuoteRepo->save($data, $factory);

        $this->assertInstanceOf(RecurringQuote::class, $recurring_quote);
        $this->assertEquals($data['total'], $recurring_quote->total);
    }

    public function test_send_recurring_quote () {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $recurring_quote->next_send_date = Carbon::now();
        $recurring_quote->date = Carbon::now()->subDays(15);
        $recurring_quote->start_date = Carbon::now()->subDays(1);
        $recurring_quote->end_date = Carbon::now()->addDays(15);
        $recurring_quote->save();

        SendRecurringQuote::dispatchNow(new QuoteRepository(new Quote()));

        $updated_recurring_quote = $recurring_quote->fresh();

        $this->assertTrue($updated_recurring_quote->next_send_date->eq(Carbon::today()->addDays($recurring_quote->frequency)));
        $this->assertTrue($updated_recurring_quote->last_sent_date->eq(Carbon::today()));
        $quote = Quote::where('recurring_quote_id', $recurring_quote->id)->first();
        $this->assertInstanceOf(Quote::class, $quote);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
