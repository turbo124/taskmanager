<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Account;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LeadTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    private $user;
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
    }

    /** @test */
    public function it_can_convert_the_account()
    {
        $account = factory(Account::class)->create();
        $account = $account->service()->convertAccount();
        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
