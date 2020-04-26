<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit;

use App\Account;
use App\Credit;
use App\Factory\CreditFactory;
use App\Factory\CustomerFactory;
use App\Filters\InvoiceFilter;
use App\Helpers\InvoiceCalculator\LineItem;
use App\NumberGenerator;
use App\Payment;
use App\Paymentable;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Requests\SearchRequest;
use App\Settings;
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
class InvoiceTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    private $customer;

    private $account;

    private $user;

    private $objNumberGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = factory(Customer::class)->create();
        $this->account = factory(Account::class)->create();
        $this->user = factory(User::class)->create();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_invoices()
    {
        factory(Invoice::class)->create();
        $list = (new InvoiceFilter(new InvoiceRepository(new Invoice)))->filter(new SearchRequest(), 1);
        $this->assertNotEmpty($list);
        $this->assertInstanceOf(Invoice::class, $list[0]);
    }

    /** @test */
    public function it_can_update_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $invoiceRepo = new InvoiceRepository($invoice);
        $updated = $invoiceRepo->save($data, $invoice);
        $found = $invoiceRepo->findInvoiceById($invoice->id);
        $this->assertInstanceOf(Invoice::class, $updated);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
    public function it_can_show_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $invoiceRepo = new InvoiceRepository(new Invoice);
        $found = $invoiceRepo->findInvoiceById($invoice->id);
        $this->assertInstanceOf(Invoice::class, $found);
        $this->assertEquals($invoice->customer_id, $found->customer_id);
    }

    public function testMarkInvoicePaidInvoice()
    {
        $user = factory(User::class)->create();
        $customer = factory(Customer::class)->create();
        $factory = (new InvoiceFactory())->create(1, $user->id, $customer);

        $data = [
            'account_id'     => 1,
            'user_id'        => $user->id,
            'customer_id'    => $this->customer->id,
            'total'          => 200,
            'balance'        => 200,
            'tax_total'      => 0,
            'discount_total' => 0,
            'status_id'      => 1,
        ];

        $invoiceRepo = new InvoiceRepository(new Invoice);
        $invoice = $invoiceRepo->save($data, $factory);
        $invoice_balance = $invoice->balance;
        $client = $invoice->customer;
        $client_balance = $client->balance;
        $invoice = $invoice->service()->markPaid($invoiceRepo, new PaymentRepository(new Payment));

        $this->assertEquals(0, $invoice->balance);

        $this->assertEquals(1, count($invoice->payments));

        foreach ($invoice->payments as $payment) {
            $this->assertEquals(round($invoice->total, 2), $payment->amount);
        }
    }

    /** @test */
    public function it_can_create_a_invoice()
    {

        $customerId = $this->customer->id;

        $total = $this->faker->randomFloat();
        $user = factory(User::class)->create();
        $factory = (new InvoiceFactory())->create(1, $user->id, $this->customer);

        $total = $this->faker->randomFloat();

        $data = [
            'account_id'     => 1,
            'user_id'        => $user->id,
            'customer_id'    => $this->customer->id,
            'total'          => $total,
            'balance'        => $total,
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $invoiceRepo = new InvoiceRepository(new Invoice);
        $invoice = $invoiceRepo->save($data, $factory);
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals($data['customer_id'], $invoice->customer_id);
    }

    /**
     * @codeCoverageIgnore
     */
    public function it_errors_creating_the_invoice_when_required_fields_are_not_passed()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $invoice = new InvoiceRepository(new Invoice);
        $invoice->createInvoice([]);
    }

    /** @test */
    public function it_errors_finding_a_invoice()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $invoice = new InvoiceRepository(new Invoice);
        $invoice->findInvoiceById(999);
    }

    /** @test */
    public function it_can_delete_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $invoiceRepo = new InvoiceRepository($invoice);
        $deleted = $invoiceRepo->newDelete($invoice);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $taskRepo = new InvoiceRepository($invoice);
        $deleted = $taskRepo->archive($invoice);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_list_all_invoices()
    {
        factory(Invoice::class, 5)->create();
        $invoiceRepo = new InvoiceRepository(new Invoice);
        $list = $invoiceRepo->listInvoices();
        $this->assertInstanceOf(Collection::class, $list);
    }

    public function testInvoicePadding()
    {
        $customer = factory(Customer::class)->create();
        $customerSettings = (new Settings())->getAccountDefaults();
        $customerSettings->counter_padding = 5;
        $customerSettings->invoice_number_counter = 7;
        $customerSettings->invoice_number_pattern = '{$clientCounter}';
        $customer->settings = $customerSettings;
        $customer->save();

        $invoice = InvoiceFactory::create($this->account->id, $this->user->id, $customer);

        $invoice_number = $this->objNumberGenerator->getNextNumberForEntity($customer, $invoice);
        $this->assertEquals($customer->getSetting('counter_padding'), 5);
        $this->assertEquals($invoice_number, '00007');
        $this->assertEquals(strlen($invoice_number), 5);
    }

    /* public function testInvoicePrefix()
    {
        $settings = CompanySettings::defaults();
        $this->account->settings = $settings;
        $this->account->save();
        $customer = factory ( Customer::class )->create ();
        $settings = CustomerSettings::defaults();
        $settings->invoice_number_counter = 1;
        $customer->settings = $settings;
        $customer->save();
        $invoice_number = $this->getNextInvoiceNumber($customer);
        $this->assertEquals($invoice_number, '000002');
    } */

    public function testReverseInvoice()
    {
        $invoice = factory(Invoice::class)->create();

        $amount = $invoice->total;
        $balance = $invoice->balance;

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->auto_archive_invoice = false;
        $account->settings = $settings;
        $account->save();

        $invoice->service()->markPaid(new InvoiceRepository(new Invoice), new PaymentRepository(new Payment))->save();

        $first_payment = $invoice->payments->first();

        $this->assertEquals((float)$first_payment->amount, (float)$invoice->total);

        $this->assertEquals((float)$first_payment->applied, (float)$invoice->total);

        $this->assertTrue($invoice->isReversable());

        $balance_remaining = $invoice->balance;
        $total_paid = $invoice->total - $invoice->balance;

        /*Adjust payment applied and the paymentables to the correct amount */

        $paymentables = Paymentable::wherePaymentableType(Invoice::class)
            ->wherePaymentableId($invoice->id)
            ->get();

        $paymentables->each(function ($paymentable) use ($total_paid) {

            $reversable_amount = $paymentable->amount - $paymentable->refunded;

            $total_paid -= $reversable_amount;

            $paymentable->amount = $paymentable->refunded;
            $paymentable->save();

        });

        /* Generate a credit for the $total_paid amount */
        $credit = CreditFactory::create($invoice->account_id, $invoice->user_id, $invoice->customer);
        $credit->customer_id = $invoice->customer_id;

       $item = (new LineItem($credit))
            ->setQuantity(1)
            ->setUnitPrice($total_paid)
            ->setNotes("Credit for reversal of " . $invoice->number);

        $credit->line_items = [$item->toObject()];

        $credit->save();

        $credit = $credit->service()->calculateInvoiceTotals();

        (new CreditRepository(new Credit))->markSent($credit);

        /* Set invoice balance to 0 */
        $invoice->ledger()->updateBalance($balance_remaining * -1, $item->getNotes())->save();

        /* Set invoice status to reversed... somehow*/
        $invoice->status_id = Invoice::STATUS_REVERSED;
        $invoice->save();

        /* Reduce client.paid_to_date by $total_paid amount */
        $invoice->customer->paid_to_date -= $total_paid;

        /* Reduce the client balance by $balance_remaining */
        $invoice->customer->balance -= $balance_remaining;

        $invoice->customer->save();

        //create a ledger row for this with the resulting Credit ( also include an explanation in the notes section )
    }

    public function testReversalViaAPI()
    {
        $invoice = factory(Invoice::class)->create();
        $invoice->customer->balance = $invoice->balance;
        $invoice->customer->save();

        $this->assertEquals($invoice->customer->balance, $invoice->balance);

        $client_paid_to_date = $invoice->customer->paid_to_date;
        $client_balance = $invoice->customer->balance;
        $invoice_balance = $invoice->balance;

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->auto_archive_invoice = false;
        $account->settings = $settings;
        $account->save();

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $invoice = $invoice->service()->markPaid(new InvoiceRepository(new Invoice), new PaymentRepository(new Payment));

        $this->assertEquals($invoice->customer->balance, ($invoice->balance * -1));
        $this->assertEquals($invoice->customer->paid_to_date, ($client_paid_to_date + $invoice_balance));
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals(Invoice::STATUS_PAID, $invoice->status_id);

        $invoice = $invoice->service()->handleReversal(new CreditRepository(new Credit), new PaymentRepository(new Payment));
        
        $this->assertEquals(Invoice::STATUS_REVERSED, $invoice->status_id);
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals($invoice->customer->paid_to_date, ($client_paid_to_date));
    }

    public function testReversalNoPayment()
    {
        $invoice = factory(Invoice::class)->create();
        $invoice->customer->balance = $invoice->balance;
        $invoice->customer->save();

        $this->assertEquals($invoice->customer->balance, $invoice->balance);

        $client_paid_to_date = $invoice->customer->paid_to_date;
        $client_balance = $invoice->customer->balance;
        $invoice_balance = $invoice->balance;

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $this->invoice = $invoice->service()->handleReversal(new CreditRepository(new Credit), new PaymentRepository(new Payment))->save();

        $this->assertEquals(Invoice::STATUS_REVERSED, $invoice->status_id);
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals($invoice->customer->paid_to_date, ($client_paid_to_date));
        $this->assertEquals($invoice->customer->balance, ($client_balance - $invoice_balance));
    }

    public function testCancelInvoice()
    {
        $invoice = factory(Invoice::class)->create();

        $this->assertTrue($invoice->isCancellable());

        $client_balance = $invoice->customer->balance;
        $invoice_balance = $invoice->balance;

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $invoice->service()->handleCancellation();

        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals($invoice->customer->balance, ($client_balance + $invoice_balance));
        $this->assertNotEquals((float)$client_balance, (float)$invoice->customer->balance);
        $this->assertEquals(Invoice::STATUS_CANCELLED, $invoice->status_id);

    }
}
