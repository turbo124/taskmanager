<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit;

use App\Factory\PurchaseOrderFactory;
use App\Models\Account;
use App\Models\Company;
use App\Models\NumberGenerator;
use App\Models\PurchaseOrder;
use App\Models\RecurringPurchaseOrder;
use App\Models\User;
use App\Repositories\PurchaseOrderRepository;
use App\Requests\SearchRequest;
use App\Search\PurchaseOrderSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Description of PurchaseOrderTest
 *
 * @author michael.hampton
 */
class PurchaseOrderTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    private $company;

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
        $this->company = Company::factory()->create();
        $this->account = Account::where('id', 1)->first();
        $this->user = User::factory()->create();
        $this->objNumberGenerator = new NumberGenerator;
    }

    /** @test */
    public function it_can_show_all_the_purchase_orders()
    {
        PurchaseOrder::factory()->create();
        $list = (new PurchaseOrderSearch(new PurchaseOrderRepository(new PurchaseOrder)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_update_the_purchase_order()
    {
        $purchase_order = PurchaseOrder::factory()->create();
        $company_id = $this->company->id;
        $data = ['company_id' => 1];
        $purchase_orderRepo = new PurchaseOrderRepository($purchase_order);
        $updated = $purchase_orderRepo->updatePurchaseOrder($data, $purchase_order);
        $found = $purchase_orderRepo->findPurchaseOrderById($purchase_order->id);
        $this->assertInstanceOf(PurchaseOrder::class, $updated);
        $this->assertEquals($data['company_id'], $found->company_id);
    }

    /** @test */
    public function it_can_show_the_purchase_order()
    {
        $purchase_order = PurchaseOrder::factory()->create();
        $purchase_orderRepo = new PurchaseOrderRepository(new PurchaseOrder);
        $found = $purchase_orderRepo->findPurchaseOrderById($purchase_order->id);
        $this->assertInstanceOf(PurchaseOrder::class, $found);
        $this->assertEquals($purchase_order->company_id, $found->company_id);
    }

    /** @test */
    public function it_can_create_a_purchase_order()
    {
        $factory = (new PurchaseOrderFactory())->create($this->account, $this->user, $this->company);

        $data = [
            'account_id'     => $this->account->id,
            'user_id'        => $this->user->id,
            'company_id'    => $this->company->id,
            'total'          => $this->faker->randomFloat(),
            'tax_total'      => $this->faker->randomFloat(),
            'discount_total' => $this->faker->randomFloat(),
            'status_id'      => 1,
        ];

        $purchase_orderRepo = new PurchaseOrderRepository(new PurchaseOrder);
        $purchase_order = $purchase_orderRepo->createPurchaseOrder($data, $factory);
        $this->assertInstanceOf(PurchaseOrder::class, $purchase_order);
        $this->assertEquals($data['company_id'], $purchase_order->company_id);
    }

    /**
     * @codeCoverageIgnore
     */
    public function it_errors_creating_the_purchase_order_when_required_fields_are_not_passed()
    {
        $this->expectException(QueryException::class);
        $purchase_order = new PurchaseOrderRepository(new PurchaseOrder);
        $purchase_order->createPurchaseOrder([]);
    }

    /** @test */
    public function it_errors_finding_a_purchase_order()
    {
        $this->expectException(ModelNotFoundException::class);
        $invoice = new PurchaseOrderRepository(new PurchaseOrder);
        $invoice->findPurchaseOrderById(99999);
    }

    /** @test */
    public function it_can_delete_the_purchase_order()
    {
        $invoice = PurchaseOrder::factory()->create();
        $invoiceRepo = new PurchaseOrderRepository($invoice);
        $deleted = $invoiceRepo->newDelete($invoice);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_purchase_order()
    {
        $purchase_order = PurchaseOrder::factory()->create();
        $taskRepo = new PurchaseOrderRepository($purchase_order);
        $deleted = $taskRepo->archive($purchase_order);
        $this->assertTrue($deleted);
    }

    public function testPurchaseOrderApproval()
    {
        $purchase_order = PurchaseOrder::factory()->create();
        $purchase_order->setStatus(PurchaseOrder::STATUS_SENT);
        $purchase_order->save();

        $account = $purchase_order->account;
        $settings = $account->settings;
        $settings->should_email_purchase_order = true;
        $settings->should_archive_purchase_order = true;
        $account->settings = $settings;
        $account->save();

        $purchase_order = $purchase_order->service()->approve(new PurchaseOrderRepository(new PurchaseOrder()));

        $this->assertNotNull($purchase_order->deleted_at);
        $this->assertEquals($purchase_order->date_approved->toDateString(), Carbon::now()->toDateString());
        $this->assertInstanceOf(PurchaseOrder::class, $purchase_order);
    }
}
