<?php

namespace Tests\Unit;

use App\Account;
use App\Credit;
use App\Design;
use App\Designs\PdfColumns;
use App\Factory\CreditFactory;
use App\Factory\DesignFactory;
use App\Filters\CreditFilter;
use App\Filters\DesignFilter;
use App\Filters\InvoiceFilter;
use App\PdfData;
use App\Repositories\CreditRepository;
use App\Repositories\DesignRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use App\Invoice;
use App\User;
use App\Customer;
use App\Repositories\InvoiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use App\Jobs\Quote\CreateQuotePdf;
use App\Jobs\Invoice\CreateInvoicePdf;

/**
 * Description of InvoiceTest
 *
 * @author michael.hampton
 */
class DesignTest extends TestCase
{

    use DatabaseTransactions,
        WithFaker;

    private $customer;
    private $account;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->account = Account::find(1);
        $this->user = factory(User::class)->create();
        $this->customer = factory(Customer::class)->create();
    }

    /** @test */
    public function it_can_show_all_the_designs()
    {
        factory(Design::class)->create();
        $list = (new DesignFilter(new DesignRepository(new Design())))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_update_the_design()
    {
        $design = factory(Design::class)->create();
        $name = $this->faker->firstName;
        $data = ['name' => $name];
        $updated = $design->update($data);
        $designRepo = new DesignRepository(new Design);
        $found = $designRepo->findDesignById($design->id);
        $this->assertTrue($updated);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_show_the_design()
    {
        $design = factory(Design::class)->create();
        $designRepo = new DesignRepository(new Design());
        $found = $designRepo->findDesignById($design->id);
        $this->assertInstanceOf(Design::class, $found);
        $this->assertEquals($design->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_design()
    {

        $user = factory(User::class)->create();
        $design = (new DesignFactory)->create(1, $user->id);

        $name = $this->faker->firstName;


        $data = [
            'name'       => $name,
            'user_id'    => $user->id,
            'account_id' => 1,
            'design'     => 'test'
        ];

        $designRepo = new DesignRepository(new Design);
        $design->fill($data);
        $saved = $design->save();
        $this->assertTrue($saved);
        $this->assertEquals($data['name'], $design->name);
    }

    public function testQuoteDesignExists()
    {
        $this->quote = factory(\App\Quote::class)->create([
            'user_id'     => $this->user->id,
            'customer_id' => $this->customer->id,
            'company_id'  => $this->account->id,
        ]);

        $this->contact = $this->quote->customer->primary_contact()->first();

        $design = Design::find(3);

        $designer = new PdfColumns(new PdfData($this->quote, $this->contact), $this->quote, $design, $this->account->settings->pdf_variables, 'quote');

        $html = $designer->buildDesign();

        $this->assertTrue($html);

        $this->quote->uses_inclusive_taxes = false;

        $settings = $this->quote->customer->settings;
        $settings->invoice_design_id = "VolejRejNm";

        $this->customer->settings = $settings;
        $this->customer->save();

        $this->quote->service()->getPdf();
    }

    public function testInvoiceDesignExists()
    {
        $this->invoice = factory(\App\Quote::class)->create([
            'user_id'     => $this->user->id,
            'customer_id' => Customer::first(),
            'company_id'  => $this->account->id,
        ]);

        $this->contact = $this->invoice->customer->primary_contact()->first();

        $design = Design::find(3);

        $designer = new PdfColumns(new PdfData($this->invoice, $this->contact), $this->invoice, $design, $this->account->settings->pdf_variables, 'invoice');

        $html = $designer->buildDesign();

        $this->assertTrue($html);

        $this->invoice->uses_inclusive_taxes = false;

        $settings = $this->invoice->customer->settings;
        $settings->invoice_design_id = "VolejRejNm";

        $this->customer->settings = $settings;
        $this->customer->save();

        $this->invoice->service()->getPdf();
    }
}
