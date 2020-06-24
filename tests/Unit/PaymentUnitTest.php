<?php

namespace Tests\Unit;

use App\Events\Payment\PaymentFailed;
use App\Factory\CustomerFactory;
use App\Factory\InvoiceFactory;
use App\Factory\CreditFactory;
use App\Filters\PaymentFilter;
use App\Helpers\Payment\ProcessPayment;
use App\Helpers\Refund\RefundFactory;
use App\Invoice;
use App\Payment;
use App\Customer;
use App\Requests\SearchRequest;
use App\User;
use App\Repositories\PaymentRepository;
use App\Repositories\InvoiceRepository;
use App\Helpers\Currency\CurrencyConverter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\EventTransformable;
use Illuminate\Foundation\Testing\WithFaker;
use App\Factory\PaymentFactory;
use App\Refund;
use App\Repositories\CreditRepository;
use App\Credit;
use App\Account;

class PaymentUnitTest extends TestCase
{

    use DatabaseTransactions, EventTransformable, WithFaker;

    private $user;

    /**
     * @var int
     */
    private $account;

    private $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->customer = factory(Customer::class)->create();
    }

    /** @test */
    public function it_can_list_all_the_payments()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'user_id'     => $this->user->id,
            'type_id'     => 1,
            'amount'      => $this->faker->randomFloat()
        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);

        $paymentRepo = new PaymentRepository(new Payment);
        (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $lists = (new PaymentFilter(new PaymentRepository(new Payment)))->filter(new SearchRequest, $this->account);
        $this->assertNotEmpty($lists);
    }

    /** @test */
    public function it_errors_when_the_payments_is_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $paymentRepo = new PaymentRepository(new Payment);
        $paymentRepo->findPaymentById(999);
    }

    /** @test */
    public function it_can_delete_the_payment()
    {
        $invoice = factory(Invoice::class)->create();
        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $original_amount = $invoice->total;

        $data = [
            'customer_id' => $this->customer->id,
            'type_id'     => 1,
            'amount'      => $invoice->total
        ];

        $data['invoices'][0]['invoice_id'] = $invoice->id;
        $data['invoices'][0]['amount'] = $invoice->total;

        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $customer_balance = $payment->customer->balance;
        $customer_paid_to_date = $payment->customer->paid_to_date;
        $payment = $payment->service()->reverseInvoicePayment();
        $this->assertEquals($payment->customer->paid_to_date, ($customer_paid_to_date - $original_amount));
        $this->assertEquals($payment->customer->balance, ($customer_balance + $original_amount));
        $this->assertEquals($invoice->balance, $original_amount);
    }

    public function it_can_archive_the_payment()
    {
        $payment = factory(Payment::class)->create();
        $taskRepo = new PaymentRepository($payment);
        $deleted = $taskRepo->archive($payment);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_get_the_payments()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'type_id'     => 1,
            'amount'      => $this->faker->randomFloat()
        ];

        $paymentRepo = new PaymentRepository(new Payment);
        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $created = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $found = $paymentRepo->findPaymentById($created->id);
        $this->assertEquals($data['customer_id'], $found->customer_id);
    }

    /** @test */
//    public function it_errors_updating_the_payments()
//    {
//        $this->expectException(\Illuminate\Database\QueryException::class);
//        $payment = factory(Payment::class)->create();
//        $paymentRepo = new PaymentRepository($payment);
//        $paymentRepo->updatePayment(['name' => null]);
//    }

    /** @test */
    public function it_can_update_the_payments()
    {
        $payment = factory(Payment::class)->create();
        $paymentRepo = new PaymentRepository($payment);
        $update = [
            'customer_id' => $this->customer->id,
        ];
        $updated = (new ProcessPayment())->process($update, $paymentRepo, $payment);
        $this->assertInstanceOf(Payment::class, $updated);
        $this->assertEquals($update['customer_id'], $updated->customer_id);
    }

    /** @test */
//    public function it_errors_when_creating_the_payments()
//    {
//        $this->expectException(\Illuminate\Database\QueryException::class);
//        $paymentRepo = new PaymentRepository(new Payment);
//        $paymentRepo->createPayment([]);
//    }
//
    /** @test */
    public function it_can_create_a_payments()
    {
        $invoice = factory(Invoice::class)->create();
        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);

        $data = [
            'customer_id' => $this->customer->id,
            'type_id'     => 1,
            'amount'      => $this->faker->randomFloat()
        ];

        $data['invoices'][0]['invoice_id'] = $invoice->id;
        $data['invoices'][0]['amount'] = $invoice->total;

        $paymentRepo = new PaymentRepository(new Payment);
        $created = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $this->assertEquals($data['customer_id'], $created->customer_id);
        $this->assertEquals($data['type_id'], $created->type_id);
    }

    public function testPaymentGreaterThanPartial()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);//stub the company and user_id
        $invoice->customer_id = $client->id;
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->partial = 5.0;
        $invoice->save();

        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $data = [
            'amount'      => 6.0,
            'customer_id' => $client->id,
            'invoices'    => [
                [
                    'invoice_id' => $invoice->id,
                    'amount'     => 6.0
                ],
            ],
            'date'        => '2019/12/12',
        ];

        $original_balance = $invoice->balance;

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $this->assertEquals($data['customer_id'], $payment->customer_id);
        $this->assertNotNull($payment->invoices());
        $this->assertEquals(1, $payment->invoices()->count());
        $invoice = $payment->invoices()->first();
        $this->assertEquals($invoice->partial, 0);
        $this->assertEquals(($original_balance - 6), $invoice->balance);
    }

    public function testCreditPayment()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $credit = CreditFactory::create($this->account, $this->user, $client);//stub the company and user_id
        $credit->customer_id = $client->id;
        $credit->status_id = Invoice::STATUS_SENT;
        $credit = $credit->service()->calculateInvoiceTotals();
        $credit->total = 50;
        $credit->save();


        $data = [
            'amount'      => 50,
            'customer_id' => $client->id,
            'credits'     => [
                [
                    'credit_id' => $credit->id,
                    'amount'    => $credit->total
                ],
            ],
            'date'        => '2020/12/12',

        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);

        $this->assertNotNull($payment);
        $this->assertEquals(50, $payment->amount);
    }

    public function testPaymentLessThanPartialAmount()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);//stub the company and user_id
        $invoice->customer_id = $client->id;

        $invoice->partial = 5.0;

        $invoice->save();

        $invoice = $invoice->service()->calculateInvoiceTotals();
        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $data = [
            'amount'      => 2.0,
            'customer_id' => $client->id,
            'invoices'    => [
                [
                    'invoice_id' => $invoice->id,
                    'amount'     => 2.0
                ],
            ],
            'date'        => '2019/12/12',
        ];

        $original_balance = $invoice->balance;

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);

        $this->assertNotNull($payment);
        $this->assertNotNull($payment->invoices());
        $this->assertEquals(1, $payment->invoices()->count());

        $invoice = $payment->invoices()->first();
        $this->assertEquals($invoice->partial, 3);
        $this->assertEquals(($original_balance - 2), $invoice->balance);
    }

    public function testBasicRefundValidation()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);//stub the company and user_id
        $invoice->customer_id = $client->id;
        $invoice->status_id = Invoice::STATUS_SENT;
        //$invoice->uses_inclusive_Taxes = false;
        $invoice->save();

        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->save();

        $data = [
            'amount'      => 50,
            'customer_id' => $client->id,
            // 'invoices' => [
            //     [
            //     'invoice_id' => $this->invoice->hashed_id,
            //     'amount' => $this->invoice->amount
            //     ],
            // ],
            'date'        => '2020/12/12',

        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);

        $this->assertNotNull($payment);
        $this->assertEquals(50, $payment->amount);


        $data = [
            'id'       => $payment->id,
            'refunded' => 50,
            // 'invoices' => [
            //     [
            //     'invoice_id' => $this->invoice->hashed_id,
            //     'amount' => $this->invoice->amount
            //     ],
            // ],
            'date'     => '2020/12/12',
        ];

        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $this->assertNotNull($payment);
        $this->assertEquals(50, $payment->refunded);
    }

    public function testRefundClassWithInvoices()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);

        $line_items[] = (new \App\Helpers\InvoiceCalculator\LineItem)
            ->setQuantity(1)
            ->setUnitPrice(2.0)
            ->calculateSubTotal()
            ->setUnitDiscount(0)
            ->setUnitTax(0)
            ->setProductId($this->faker->word())
            ->setNotes($this->faker->realText(50))
            ->toObject();

        $invoice->line_items = $line_items;
        $invoice = $invoice->service()->calculateInvoiceTotals();
        $invoice->save();

        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->should_archive_invoice = false;
        $account->settings = $settings;
        $account->save();

        $data = [
            'amount'      => 2.0,
            'customer_id' => $invoice->customer->id,
            'invoices'    => [
                [
                    'invoice_id' => $invoice->id,
                    'amount'     => 2.0
                ],
            ],
            'date'        => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);
        $original_customer_balance = abs($payment->customer->balance);
        $original_paid_to_date = abs($payment->customer->paid_to_date);

        $payment = (new RefundFactory())->createRefund(
            $payment,
            [
                'amount'   => 2,
                'invoices' => [
                    [
                        'invoice_id' => $invoice->id,
                        'amount'     => 2.0
                    ],
                ]
            ],
            new CreditRepository(new Credit)
        );

        $this->assertEquals($invoice->balance, 2);
        $this->assertEquals($invoice->status_id, 2);
        $this->assertEquals(2, $payment->refunded);
        $this->assertEquals(($original_customer_balance - 2), $payment->customer->balance);
        $this->assertEquals(Payment::STATUS_REFUNDED, $payment->status_id);
        $this->assertEquals(($original_paid_to_date - 2), $payment->customer->paid_to_date);
    }

    public function testRefundClassWithoutInvoices()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);//stub the company and user_id

        $invoice->save();

        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->should_archive_invoice = false;
        $account->settings = $settings;
        $account->save();


        $data = [
            'amount'      => 2.0,
            'customer_id' => $invoice->customer->id,
            'invoices'    => [
                [
                    'invoice_id' => $invoice->id,
                    'amount'     => 2.0
                ],
            ],
            'date'        => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process($data, $paymentRepo, $factory);

        $original_customer_balance = abs($payment->customer->balance);
        $original_paid_to_date = abs($payment->customer->paid_to_date);

        $payment = (new RefundFactory())->createRefund(
            $payment,
            [
                'amount' => 2,
            ],
            new CreditRepository(new Credit)
        );

        $this->assertEquals(2, $payment->refunded);
        $this->assertEquals(Payment::STATUS_REFUNDED, $payment->status_id);
        $this->assertEquals(($original_customer_balance - 2), $payment->customer->balance);
        $this->assertEquals(($original_paid_to_date - 2), $payment->customer->paid_to_date);
    }

    public function testConversion()
    {
        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = (new ProcessPayment())->process(['amount' => 800], $paymentRepo, $factory);

        $converted = (new CurrencyConverter)
            ->setBaseCurrency($payment->account->getCurrency())
            ->setExchangeCurrency($payment->customer->currency)
            ->setAmount(2999.99)
            ->calculate();

        $this->assertNotNull($converted);
    }

//    public function testAuthorizeRefund()
//    {
//       $payment = Payment::find(3386);
//       $test = (new RefundFactory())->createRefund($payment, [], new CreditRepository(new Credit()));
//
//       echo '<pre>';
//       print_r($payment);
//       die;
//    }
}

