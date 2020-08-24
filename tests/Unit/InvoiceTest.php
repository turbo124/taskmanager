<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit;

use App\Factory\CreditFactory;
use App\Factory\InvoiceFactory;
use App\Filters\InvoiceFilter;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Jobs\Invoice\SendReminders;
use App\Models\Account;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NumberGenerator;
use App\Models\Payment;
use App\Models\Paymentable;
use App\Models\RecurringInvoice;
use App\Models\User;
use App\Repositories\CreditRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\PaymentRepository;
use App\Requests\SearchRequest;
use App\Settings\AccountSettings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Description of InvoiceTest
 *
 * @author michael.hampton
 */
class InvoiceTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    /**
     * @var \App\Models\Customer|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private Customer $customer;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Account
     */
    private Account $main_account;

    /**
     * @var User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private User $user;

    /**
     * @var NumberGenerator
     */
    private NumberGenerator $objNumberGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->customer = factory(Customer::class)->create();
        $this->account = factory(Account::class)->create();
        $this->user = factory(User::class)->create();
        $this->main_account = Account::where('id', 1)->first();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_invoices()
    {
        factory(Invoice::class)->create();
        $list = (new InvoiceFilter(new InvoiceRepository(new Invoice)))->filter(
            new SearchRequest(),
            $this->main_account
        );
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_update_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $customer_id = $this->customer->id;
        $data = ['customer_id' => $customer_id];
        $invoiceRepo = new InvoiceRepository($invoice);
        $updated = $invoiceRepo->updateInvoice($data, $invoice);
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
        $factory = (new InvoiceFactory())->create($this->main_account, $user, $customer);

        $data = [
            'account_id'     => $this->main_account->id,
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
        $invoice = $invoice->service()->createPayment($invoiceRepo, new PaymentRepository(new Payment));

        $this->assertEquals(0, $invoice->balance);

        $this->assertEquals(1, count($invoice->payments));

        foreach ($invoice->payments as $payment) {
            $this->assertEquals(round($invoice->total, 2), $payment->amount);
        }
    }

    /** @test */
    public function it_can_create_a_invoice()
    {
        $user = factory(User::class)->create();
        $factory = (new InvoiceFactory())->create($this->main_account, $user, $this->customer);

        $total = $this->faker->randomFloat();

        $data = [
            'date'           => Carbon::now()->format('Y-m-d'),
            'due_date'       => Carbon::now()->addDays(3)->format('Y-m-d'),
            'account_id'     => $this->customer->account->id,
            'user_id'        => $user->id,
            'customer_id'    => $this->customer->id,
            'total'          => $total,
            'balance'        => $total,
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $invoiceRepo = new InvoiceRepository(new Invoice);
        $invoice = $invoiceRepo->createInvoice($data, $factory);
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals($data['customer_id'], $invoice->customer_id);
    }

    public function test_it_can_create_a_recurring_invoice()
    {
        $user = factory(User::class)->create();
        $factory = (new InvoiceFactory())->create($this->main_account, $user, $this->customer);

        $total = $this->faker->randomFloat();

        $data = [
            'account_id'     => $this->main_account->id,
            'user_id'        => $user->id,
            'customer_id'    => $this->customer->id,
            'total'          => $total,
            'balance'        => $total,
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $invoiceRepo = new InvoiceRepository(new Invoice);
        $invoice = $invoiceRepo->createInvoice($data, $factory);

        $arrRecurring = [];

        $arrRecurring['start_date'] = date('Y-m-d');
        $arrRecurring['end_date'] = date('Y-m-d', strtotime('+1 year'));;
        $arrRecurring['frequency'] = 30;
        $arrRecurring['recurring_due_date'] = date('Y-m-d', strtotime('+1 month'));
        $recurring_invoice = $invoice->service()->createRecurringInvoice($arrRecurring);
        $this->assertInstanceOf(RecurringInvoice::class, $recurring_invoice);
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
        $deleted = $invoice->deleteInvoice();
        // balance is more than 0
        $this->assertFalse($deleted);

        $invoice->balance = 0;
        $invoice->save();
        $deleted = $invoice->deleteInvoice();
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_archive_the_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $taskRepo = new InvoiceRepository($invoice);
        $deleted = $taskRepo->archive($invoice);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function testInvoicePadding()
    {
        $customer = factory(Customer::class)->create();
        $customerSettings = (new AccountSettings)->getAccountDefaults();
        $customerSettings->counter_padding = 5;
        $customerSettings->invoice_number_counter = 7;
        $customerSettings->invoice_number_pattern = '{$clientCounter}';
        $customer->settings = $customerSettings;
        $customer->save();

        $invoice = InvoiceFactory::create($this->main_account, $this->user, $customer);

        $invoice_number = $this->objNumberGenerator->getNextNumberForEntity($invoice, $customer);
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

    /** @test */
    public function testReverseInvoice()
    {
        $invoice = factory(Invoice::class)->create();

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->should_archive_invoice = false;
        $account->settings = $settings;
        $account->save();

        $invoice->service()->createPayment(
            new InvoiceRepository(new Invoice),
            new PaymentRepository(new Payment)
        )->save();

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

        $paymentables->each(
            function ($paymentable) use ($total_paid) {
                $paymentable->amount = $paymentable->refunded;
                $paymentable->save();
            }
        );

        /* Generate a credit for the $total_paid amount */
        $credit = CreditFactory::create($invoice->account, $invoice->user, $invoice->customer);
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
        $invoice->transaction_service()->createTransaction($balance_remaining * -1, $item->getNotes())->save();

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

    /** @test */
    public function testReversalViaAPI()
    {
        $invoice = factory(Invoice::class)->create();
        $invoice->customer->balance = $invoice->balance;
        $invoice->customer->save();

        $this->assertEquals($invoice->customer->balance, $invoice->balance);

        $client_paid_to_date = $invoice->customer->paid_to_date;
        $client_balance = $invoice->customer->balance;
        $invoice_balance = $invoice->balance;
        $invoice_total = $invoice->total;

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->should_archive_invoice = false;
        $account->settings = $settings;
        $account->save();

        (new InvoiceRepository(new Invoice()))->markSent($invoice);

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $invoice = $invoice->service()->createPayment(
            new InvoiceRepository(new Invoice),
            new PaymentRepository(new Payment)
        );

        $this->assertEquals($invoice->customer->balance, $client_balance);
        $this->assertEquals($invoice->customer->paid_to_date, ($client_paid_to_date + $invoice_balance));
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals(Invoice::STATUS_PAID, $invoice->status_id);

        $invoice = $invoice->service()->reverseInvoicePayment(
            new CreditRepository(new Credit),
            new PaymentRepository(new Payment)
        );

        $this->assertEquals(Invoice::STATUS_REVERSED, $invoice->status_id);
        $this->assertEquals(0, $invoice->balance);

        $new_customer_balance = $client_balance - ($invoice_total - $invoice_balance);

        $this->assertEquals($invoice->customer->paid_to_date, $new_customer_balance);
    }

    /** @test */
    public function testReversalNoPayment()
    {
        $invoice = factory(Invoice::class)->create();
        $client_paid_to_date = $invoice->customer->paid_to_date;
        $client_balance = $invoice->customer->balance;
        $invoice_balance = $invoice->balance;

        (new InvoiceRepository(new Invoice()))->markSent($invoice);

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $this->invoice = $invoice->service()->reverseInvoicePayment(
            new CreditRepository(new Credit),
            new PaymentRepository(new Payment)
        )->save();

        $this->assertEquals(Invoice::STATUS_REVERSED, $invoice->status_id);
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals($invoice->customer->paid_to_date, ($client_paid_to_date));
        $this->assertEquals($invoice->customer->balance, $client_balance);
    }

    /** @test */
    public function testCancelInvoice()
    {
        $invoice = factory(Invoice::class)->create();
        $client_balance = $invoice->customer->balance;

        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $this->assertTrue($invoice->isCancellable());

        (new InvoiceRepository(new Invoice()))->markSent($invoice);
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);

        $invoice = $invoice->service()->cancelInvoice();

        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals($invoice->customer->balance, $client_balance);
        $this->assertEquals(Invoice::STATUS_CANCELLED, $invoice->status_id);
    }

    /** @test */
    public function testCancellationReversal()
    {
        $invoice = factory(Invoice::class)->create();

        $previous_balance = $invoice->balance;
        $customer_balance = $invoice->customer->balance;

        (new InvoiceRepository(new Invoice()))->markSent($invoice);
        $balance_with_invoice = $invoice->customer->balance;

        $invoice->service()->cancelInvoice();
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals(Invoice::STATUS_CANCELLED, $invoice->status_id);
        $this->assertEquals($customer_balance, $invoice->customer->balance);
        $invoice->service()->reverseStatus();

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status_id);
        $this->assertEquals($previous_balance, $invoice->balance);
        $this->assertNull($invoice->previous_status);
        $this->assertNull($invoice->previous_balance);
        $this->assertEquals($invoice->customer->balance, $balance_with_invoice);
    }

    /** @test */
//    public function autoBill()
//    {
//        // create invoice
//        $invoice = factory(Invoice::class)->create();
//        $invoice->customer_id = 5;
//        $invoice->gateway_fee = 12.99;
//        $invoice->save();
//
//        $total = $invoice->total;
//        $line_item_count = count($invoice->line_items);
//
//        $invoiceRepo = new InvoiceRepository(new Invoice);
//        $original_invoice = $invoiceRepo->createInvoice([], $invoice);
//        $expected_amount = $total + $original_invoice->gateway_fee;
//        $this->assertEquals((float)$expected_amount, (float)$original_invoice->total);
//        $this->assertEquals($line_item_count + 1, count($original_invoice->line_items));
//
//        // auto bill
//        AutobillInvoice::dispatchNow($original_invoice, $invoiceRepo);
//
//        $payment = $original_invoice->payments->first();
//
//        $invoice = $original_invoice->fresh();
//        $this->assertNotNull($payment);
//        $this->assertInstanceOf(Payment::class, $payment);
//        $this->assertEquals((float)$payment->amount, $expected_amount);
//        $this->assertEquals(0, $invoice->balance);
//    }

    /** @test */
//    public function autoBill_with_gateway()
//    {
//        // create invoice
//        $invoice = factory(Invoice::class)->create();
//        $invoice->customer_id = 5;
//        $invoice->gateway_fee = 0;
//        $user = factory(User::class)->create();
//
//        $total = $invoice->total;
//        $line_item_count = count($invoice->line_items);
//
//        $invoiceRepo = new InvoiceRepository(new Invoice);
//        $original_invoice = $invoiceRepo->createInvoice([], $invoice);
//        $this->assertEquals($total, $original_invoice->total);
//        $this->assertEquals($line_item_count, count($original_invoice->line_items));
//
//        // auto bill
//        AutobillInvoice::dispatchNow($original_invoice, $invoiceRepo);
//        $invoice = $original_invoice->fresh();
//        $this->assertEquals($line_item_count + 1, count($invoice->line_items));
//        $this->assertEquals($total + $invoice->gateway_fee, $invoice->total);
//
//        $payment = $original_invoice->payments->first();
//
//        $invoice = $original_invoice->fresh();
//        $this->assertNotNull($payment);
//        $this->assertInstanceOf(Payment::class, $payment);
//        $this->assertEquals((float)$payment->amount, $invoice->total);
//        $this->assertEquals(0, $invoice->balance);
//    }

    public function test_reminders()
    {
        // create invoice
        $invoice = factory(Invoice::class)->create();
        $invoice->customer_id = 5;
        $invoice->account_id = $this->account->id;
        $invoice->next_send_date = Carbon::now();
        $invoice->save();

        $settings = $this->account->settings;
        $settings->late_fee_amount1 = 10;
        $settings->enable_reminder1 = true;
        $settings->num_days_reminder1 = 1;
        $settings->schedule_reminder1 = 'after_invoice_date';
        $settings->inclusive_taxes = false;
        $this->account->settings = $settings;
        $this->account->save();

        $invoiceRepo = new InvoiceRepository(new Invoice);

        SendReminders::dispatchNow($invoiceRepo);

        $updated_invoice = $invoice->fresh();

        $next_send_date = Carbon::parse($invoice->date)->addDays($settings->num_days_reminder1)->format('Y-m-d');

        $this->assertEquals(count($invoice->line_items) + 1, count($updated_invoice->line_items));
        $this->assertEquals($this->main_account->settings->late_fee_amount1, $updated_invoice->late_fee_charge);
        $this->assertEquals($updated_invoice->next_send_date, $next_send_date);
    }
}
