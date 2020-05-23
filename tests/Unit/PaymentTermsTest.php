<?php

namespace Tests\Unit;

use App\Customer;
use App\Factory\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use App\Account;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class PaymentTermsTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    /**
     * @var int
     */
    private $account;

    private $user;

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
    public function it_can_show_all_the_groups()
    {
        factory(PaymentTerm::class)->create();
        $list = (new PaymentTermsFilter(new PaymentTermsRepository(new PaymentTerms)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_group()
    {
        $payment_term = factory(PaymentTerm::class)->create();
        $payment_terms_repo = new PaymentTermsRepository($payment_term);
        $deleted = $payment_terms_repo->newDelete($payment_term);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_group()
    {
        $payment_term = factory(GroupSetting::class)->create();
        $payment_terms_repo = new PaymentTermsRepository($payment_term);
        $deleted = $payment_terms_repo->archive($payment_term);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_group()
    {
        $payment_term = factory(PaymentTerm::class)->create();
        $data = ['name' => $this->faker->word()];
        $payment_terms_repo = new PaymentTermsRepository($payment_term);
        $updated = $payment_terms_repo->save($data, $payment_term);
        $found = $payment_terms_repo->findPaymentTermsById($payment_term->id);
        $this->assertInstanceOf(PaymentTerms::class, $updated);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_show_the_group()
    {
        $payment_term = factory(PaymentTerms::class)->create();
        $payment_terms_repo = PaymentTermsRepository(new PaymentTerms);
        $found = $payment_terms_repo->findPaymentTermsById($payment_term->id);
        $this->assertInstanceOf(PaymentTerms::class, $found);
        $this->assertEquals($payment_term->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_group()
    {
        $user = factory(User::class)->create();
        $factory = (new PaymentTermsFactory)->create($this->account, $user);

        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $user->id,
            'name'        => $this->faker->word()
        ];

        $payment_terms_repo = new PaymentTermsRepository(new PaymentTerms);
        $payment_term = $payment_terms_repo->save($data, $factory);
        $this->assertInstanceOf(PaymentTerms::class, $payment_term);
        $this->assertEquals($data['name'], $payment_term->name);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
