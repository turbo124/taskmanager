<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit;

use App\Filters\QuoteFilter;
use App\Models\Account;
use App\Models\NumberGenerator;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use App\Models\Quote;
use App\Models\User;
use App\Models\Customer;
use App\Models\Invoice;
use App\Repositories\QuoteRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use App\Factory\QuoteFactory;

/**
 * Description of QuoteTest
 *
 * @author michael.hampton
 */
class QuoteTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    private $customer;

    private $user;

    private $objNumberGenerator;

    /**
     * @var int
     */
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = factory(Customer::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->user = factory(User::class)->create();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_quotes()
    {
        factory(Quote::class)->create();
        $list = (new QuoteFilter(new QuoteRepository(new Quote)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_update_the_quote()
    {
        $quote = factory(Quote::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $quoteRepo = new QuoteRepository($quote);
        $updated = $quoteRepo->updateQuote($data, $quote);
        $found = $quoteRepo->findQuoteById($quote->id);
        $this->assertInstanceOf(Quote::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_quote()
    {
        $quote = factory(Quote::class)->create();
        $quoteRepo = new QuoteRepository(new Quote);
        $found = $quoteRepo->findQuoteById($quote->id);
        $this->assertInstanceOf(Quote::class, $found);
        $this->assertEquals($quote->customer_id, $found->customer_id);
    }

    /** @test */
    public function it_can_create_a_quote()
    {
        $total = $this->faker->randomFloat();
        $factory = (new QuoteFactory())->create($this->account, $this->user, $this->customer);

        $data = [
            'account_id'     => $this->account->id,
            'user_id'        => $this->user->id,
            'customer_id'    => $this->customer->id,
            'total'          => $this->faker->randomFloat(),
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $quoteRepo = new QuoteRepository(new Quote);
        $quote = $quoteRepo->createQuote($data, $factory);
        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertEquals($data['customer_id'], $quote->customer_id);
    }

    /**
     * @codeCoverageIgnore
     */
    public function it_errors_creating_the_quote_when_required_fields_are_not_passed()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $quote = new QuoteRepository(new Quote);
        $quote->createQuote([]);
    }

    /** @test */
    public function it_errors_finding_a_quote()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $invoice = new QuoteRepository(new Quote);
        $invoice->findQuoteById(99999);
    }

    /** @test */
    public function it_can_delete_the_quote()
    {
        $invoice = factory(Quote::class)->create();
        $invoiceRepo = new QuoteRepository($invoice);
        $deleted = $invoiceRepo->newDelete($invoice);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_quote()
    {
        $quote = factory(Quote::class)->create();
        $taskRepo = new QuoteRepository($quote);
        $deleted = $taskRepo->archive($quote);
        $this->assertTrue($deleted);
    }

    public function testQuoteApproval()
    {
        $quote = factory(Quote::class)->create();
        $quote->setStatus(Quote::STATUS_SENT);
        $quote->save();

        $account = $quote->account;
        $settings = $account->settings;
        $settings->should_convert_quote = true;
        $settings->should_email_quote = true;
        $settings->should_archive_quote = true;
        $account->settings = $settings;
        $account->save();

        $quote = $quote->service()->approve(new InvoiceRepository(new Invoice), new QuoteRepository(new Quote));
        $this->assertNotNull($quote->invoice_id);
        $this->assertInstanceOf(Quote::class, $quote);
    }

    public function testQuoteToOrderConversion()
    {
        $quote = factory(Quote::class)->create();
        $quote->setStatus(Quote::STATUS_SENT);
        $quote->save();

        $account = $quote->account;
        $settings = $account->settings;
        $settings->should_convert_quote = true;
        $settings->should_email_quote = true;
        $settings->should_archive_quote = true;
        $account->settings = $settings;
        $account->save();

        $order = $quote->service()->convertQuoteToOrder(new OrderRepository(new Order));
        $this->assertNotNull($quote->order_id);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(6, $quote->status_id);
    }
}
