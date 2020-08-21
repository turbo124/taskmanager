<?php

namespace Tests\Unit;

use App\Factory\RecurringQuoteFactory;
use App\Filters\RecurringQuoteFilter;
use App\Models\Account;
use App\Models\Customer;
use App\Models\RecurringQuote;
use App\Models\User;
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
    public function it_can_show_all_the_invoices()
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
    public function it_can_delete_the_invoice()
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
    public function it_can_show_the_invoice()
    {
        $recurring_quote = factory(RecurringQuote::class)->create();
        $recurringQuoteRepo = new RecurringQuoteRepository(new RecurringQuote());
        $found = $recurringQuoteRepo->findQuoteById($recurring_quote->id);
        $this->assertInstanceOf(RecurringQuote::class, $found);
        $this->assertEquals($recurring_quote->due_date, $found->due_date);
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

        $recurringQuoteRepo = new RecurringQuoteRepository(new RecurringQuote());
        $factory = (new RecurringQuoteFactory())->create($this->customer, $this->account, $this->user, 200);
        $recurring_quote = $recurringQuoteRepo->save($data, $factory);

        $this->assertInstanceOf(RecurringQuote::class, $recurring_quote);
        $this->assertEquals($data['total'], $recurring_quote->total);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
