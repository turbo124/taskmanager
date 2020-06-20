<?php

namespace Tests\Unit;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Account;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    /** @test */
    public function it_can_convert_the_account()
    {
        $account = factory(Account::class)->create();
        $account = $account->service()->convertAccount();
        $this->assertInstanceOf(Account::class, $account);
        $this->assertInstanceOf(Customer::class, $account->domains->customer);
        $this->assertInstanceOf(User::class, $account->domains->user);
        $this->assertEquals(1, $account->domains->customer->contacts->count());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
