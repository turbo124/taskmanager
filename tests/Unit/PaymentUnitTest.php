<?php

namespace Tests\Unit;

use App\Factory\CustomerFactory;
use App\Factory\InvoiceFactory;
use App\Factory\CreditFactory;
use App\Filters\PaymentFilter;
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
            'user_id' => $this->user->id,
            'type_id' => 1,
            'amount' => $this->faker->randomFloat()
        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);

        $paymentRepo = new PaymentRepository(new Payment);
        $paymentRepo->processPayment($data, $factory);
        $lists = (new PaymentFilter(new PaymentRepository(new Payment)))->filter(new SearchRequest, $this->account->id);
        $this->assertInstanceOf(Payment::class, $lists[0]);
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
        $payment = factory(Payment::class)->create();
        $invoiceRepo = new PaymentRepository($payment);
        $deleted = $invoiceRepo->newDelete($payment);
        $this->assertTrue($deleted);
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
            'type_id' => 1,
            'amount' => $this->faker->randomFloat()
        ];

        $paymentRepo = new PaymentRepository(new Payment);
        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $created = $paymentRepo->processPayment($data, $factory);
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
        $updated = $paymentRepo->processPayment($update, $payment);
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
            'type_id' => 1,
            'amount' => $this->faker->randomFloat()
        ];

        $data['invoices'][0]['invoice_id'] = $invoice->id;
        $data['invoices'][0]['amount'] = $invoice->total;

        $paymentRepo = new PaymentRepository(new Payment);
        $created = $paymentRepo->processPayment($data, $factory);
        $this->assertEquals($data['customer_id'], $created->customer_id);
        $this->assertEquals($data['type_id'], $created->type_id);
    }

//    public function testPartialPaymentAmount()
//    {
//        $client = CustomerFactory::create($this->account_id, $this->user->id);
//        $client->save();
//
//        $invoice = InvoiceFactory::create($this->customer->id, $this->user->id,
//            $this->account_id);//stub the company and user_id
//        $invoice->customer_id = $client->id;
//
//        $invoice->total = 10;
//        $invoice->partial = 2.0;
//        //$invoice->uses_inclusive_taxes = false;
//
//        $invoice->save();
//
//        $invoice_calc = new InvoiceSum($invoice);
//        $invoice_calc->build();
//
//        $invoice = $invoice_calc->getInvoice();
//        $invoice->total = 2;
//        $invoice->save();
//        $invoice->markSent();
//        $invoice->save();
//
//
//        $data = [
//            'amount' => 2.0,
//            'customer_id' => $client->id,
//            'invoices' => [
//                [
//                    'invoice_id' => $invoice->id,
//                    'amount' => 2.0
//                ],
//            ],
//            'date' => '2019/12/12',
//        ];
//
//        $factory = (new PaymentFactory())->create($this->customer->id, $this->user->id, $this->account_id);
//        $paymentRepo = new PaymentRepository(new Payment);
//        $payment = $paymentRepo->save($data, $factory);
//        $this->assertEquals($data['customer_id'], $payment->customer_id);
//        $this->assertNotNull($payment);
//        $this->assertNotNull($payment->invoices());
//        $this->assertEquals(1, $payment->invoices()->count());
//
//        $pivot_invoice = $payment->invoices()->first();
//        //$this->assertEquals($pivot_invoice->pivot->total, 2);
//        $this->assertEquals($pivot_invoice->partial, 0);
//        //$this->assertEquals($pivot_invoice->amount, 10.0000);
//        $this->assertEquals($pivot_invoice->balance, 8.0000);
//    }

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
            'amount' => 6.0,
            'customer_id' => $client->id,
            'invoices' => [
                [
                    'invoice_id' => $invoice->id,
                    'amount' => 6.0
                ],
            ],
            'date' => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);
        $this->assertEquals($data['customer_id'], $payment->customer_id);
        $this->assertNotNull($payment->invoices());
        $this->assertEquals(1, $payment->invoices()->count());

        $invoice = $payment->invoices()->first();

        $this->assertEquals($invoice->partial, 0);
        //$this->assertEquals($invoice->balance, 4);
    }

    public function testCreditPayment()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $credit = CreditFactory::create($this->account, $this->user, $client);//stub the company and user_id
        $credit->customer_id = $client->id;
        $credit->status_id = Invoice::STATUS_SENT;
        //$invoice->uses_inclusive_Taxes = false;
        $credit->save();

        $credit = $credit->service()->calculateInvoiceTotals();
        $credit->save();

        $data = [
            'amount' => 50,
            'customer_id' => $client->id,
             'credits' => [
                 [
                 'credit_id' => $credit->id,
                 'amount' => $credit->total
                 ],
         ],
            'date' => '2020/12/12',

        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);

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
        //$invoice->line_items = $this->buildLineItems();
        //$invoice->uses_inclusive_taxes = false;

        $invoice->save();

        $invoice = $invoice->service()->calculateInvoiceTotals();
        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $data = [
            'amount' => 2.0,
            'customer_id' => $client->id,
            'invoices' => [
                [
                    'invoice_id' => $invoice->id,
                    'amount' => 2.0
                ],
            ],
            'date' => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);

        $this->assertNotNull($payment);
        $this->assertNotNull($payment->invoices());
        $this->assertEquals(1, $payment->invoices()->count());

        $invoice = $payment->invoices()->first();

        $this->assertEquals($invoice->partial, 3);
        //$this->assertEquals($invoice->balance, 8);

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
            'amount' => 50,
            'customer_id' => $client->id,
            // 'invoices' => [
            //     [
            //     'invoice_id' => $this->invoice->hashed_id,
            //     'amount' => $this->invoice->amount
            //     ],
            // ],
            'date' => '2020/12/12',

        ];

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);

        $this->assertNotNull($payment);
        $this->assertEquals(50, $payment->amount);


        $data = [
            'id' => $payment->id,
            'refunded' => 50,
            // 'invoices' => [
            //     [
            //     'invoice_id' => $this->invoice->hashed_id,
            //     'amount' => $this->invoice->amount
            //     ],
            // ],
            'date' => '2020/12/12',
        ];

        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);
        $this->assertNotNull($payment);
        $this->assertEquals(50, $payment->refunded);
    }

    public function testRefundClassWithInvoices()
    {
        $client = CustomerFactory::create($this->account, $this->user);
        $client->save();

        $invoice = InvoiceFactory::create($this->account, $this->user, $client);//stub the company and user_id
        //$invoice->customer_id = $client->id;

        $invoice->save();

        (new InvoiceRepository(new Invoice))->markSent($invoice);

        $account = $invoice->account;
        $settings = $account->settings;
        $settings->auto_archive_invoice = false;
        $account->settings = $settings;
        $account->save();


        $data = [
            'amount' => 2.0,
            'customer_id' => $invoice->customer->id,
            'invoices' => [
                [
                    'invoice_id' => $invoice->id,
                    'amount' => 2.0
                ],
            ],
            'date' => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);

        (new Refund($payment, (
            new CreditRepository(new Credit)), 
            [
                'amount' => 2,
                'invoices' => [
                [
                    'invoice_id' => $invoice->id,
                    'amount' => 2.0
                ],
            ]]
        ))->refund();
       
        $this->assertEquals(2, $payment->refunded);
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
        $settings->auto_archive_invoice = false;
        $account->settings = $settings;
        $account->save();


        $data = [
            'amount' => 2.0,
            'customer_id' => $invoice->customer->id,
            'invoices' => [
                [
                    'invoice_id' => $invoice->id,
                    'amount' => 2.0
                ],
            ],
            'date' => '2019/12/12',
        ];

        $factory = (new PaymentFactory())->create($client, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment($data, $factory);

        (new Refund($payment, (
            new CreditRepository(new Credit)), 
            [
                'amount' => 2,
            ]
        ))->refund();
       
        $this->assertEquals(2, $payment->refunded);
    }

    public function testConversion ()
    {

        $factory = (new PaymentFactory())->create($this->customer, $this->user, $this->account);
        $paymentRepo = new PaymentRepository(new Payment);
        $payment = $paymentRepo->processPayment(['amount' => 800], $factory);

        $converted = (new CurrencyConverter)
        ->setBaseCurrency($payment->account->getCurrency())
        ->setExchangeCurrency($payment->customer->currency)
        ->setAmount(2999.99)
        ->calculate();

         $this->assertNotNull($converted);
    }
}

