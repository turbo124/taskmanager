<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit;

use App\Filters\QuoteFilter;
use App\NumberGenerator;
use App\Requests\SearchRequest;
use Tests\TestCase;
use App\Quote;
use App\User;
use App\Customer;
use App\Repositories\QuoteRepository;
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
    private $account_id = 1;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = factory(Customer::class)->create();
        $this->user = factory(User::class)->create();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_quotes()
    {
        factory(Quote::class)->create();
        $list = (new QuoteFilter(new QuoteRepository(new Quote)))->filter(new SearchRequest(), 1);
        $this->assertNotEmpty($list);
        $this->assertInstanceOf(Quote::class, $list[0]);
    }

    /** @test */
    public function it_can_update_the_quote()
    {
        $quote = factory(Quote::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $quoteRepo = new QuoteRepository($quote);
        $updated = $quoteRepo->save($data, $quote);
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
        $factory = (new QuoteFactory())->create($this->account_id, $this->user->id, $this->customer);

        $data = [
            'account_id' => $this->account_id,
            'user_id' => $this->user->id,
            'customer_id' => $this->customer->id,
            'total' => $this->faker->randomFloat(),
            'tax_total' => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id' => 1,
        ];

        $quoteRepo = new QuoteRepository(new Quote);
        $quote = $quoteRepo->save($data, $factory);
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

//    public function testQuoteNumberValue()
//    {
//        $customer = factory(Customer::class)->create();
//        $invoice_number = $this->getNextQuoteNumber($customer);
//        $this->assertEquals($invoice_number, 000001);
//        $invoice_number = $this->getNextQuoteNumber($customer);
//        $this->assertEquals($invoice_number, 000002);
//    }

    public function testQuoteNumberPattern()
    {
        $settings = $this->customer->account->settings;
        $settings->quote_number_counter = 1;
        $settings->quote_number_pattern = '{$year}-{$counter}';
        $this->customer->account->settings = $settings;
        $this->customer->account->save();
        $this->customer->settings = $settings;
        $this->customer->save();
        $this->customer->fresh();

        $quote = QuoteFactory::create($this->customer->account->id, $this->user->id, $this->customer);

        $invoice_number = $this->objNumberGenerator->getNextNumberForEntity($this->customer, $quote);
        $invoice_number2 = $this->objNumberGenerator->getNextNumberForEntity($this->customer, $quote);
        $this->assertEquals($invoice_number, date('Y') . '-0001');
        $this->assertEquals($invoice_number2, date('Y') . '-0002');
        //$this->assertEquals($this->customer->account->settings->invoice_number_counter,3);

    }

    public function testQuoteNumberPatternWithSharedCounter()
    {
        $settings = $this->customer->account->settings;
        $settings->quote_number_counter = 100;
        $settings->quote_number_pattern = '{$year}-{$counter}';
        $settings->counter_padding = 4;
        $settings->shared_invoice_quote_counter = true;
        $this->customer->account->settings = $settings;
        $this->customer->account->save();
        $this->customer->settings = $settings;
        $this->customer->save();

        $quote = QuoteFactory::create($this->customer->account->id, $this->user->id, $this->customer);
        $quote_number = $this->objNumberGenerator->getNextNumberForEntity($this->customer, $quote);
        $quote_number2 = $this->objNumberGenerator->getNextNumberForEntity($this->customer, $quote);
        $this->assertEquals($quote_number, date('Y') . '-0100');
        $this->assertEquals($quote_number2, date('Y') . '-0101');
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
}
