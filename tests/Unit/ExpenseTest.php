<?php

namespace Tests\Unit;

use App\Factory\ExpenseFactory;
use App\Filters\ExpenseFilter;
use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\User;
use App\Repositories\ExpenseRepository;
use App\Requests\SearchRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $customer;

    private $user;

    private $company;

    /**
     * @var int
     */
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = Customer::factory()->create();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->account = Account::where('id', 1)->first();
    }

    public function it_can_show_all_the_expenses()
    {
        Expense::factory()->create();
        $list = (new ExpenseFilter(new ExpenseRepository(new Expense)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_update_the_expense()
    {
        $expense = Expense::factory()->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $expenseRepo = new ExpenseRepository($expense);
        $updated = $expenseRepo->save($data, $expense);
        $found = $expenseRepo->findExpenseById($expense->id);
        $this->assertInstanceOf(Expense::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_expense()
    {
        $expense = Expense::factory()->create();
        $quoteRepo = new ExpenseRepository(new Expense);
        $found = $quoteRepo->findExpenseById($expense->id);
        $this->assertInstanceOf(Expense::class, $found);
        $this->assertEquals($expense->customer_id, $found->customer_id);
    }

    /** @test */
    public function it_can_create_a_expense()
    {
        $factory = (new ExpenseFactory)->create($this->account, $this->user);

        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $this->user->id,
            'customer_id' => $this->customer->id,
            'amount'      => $this->faker->randomFloat(),
            'company_id'  => $this->company->id
        ];

        $expenseRepo = new ExpenseRepository(new Expense);
        $expense = $expenseRepo->save($data, $factory);
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertEquals($data['customer_id'], $expense->customer_id);
    }
}
