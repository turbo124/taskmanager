<?php

namespace Tests\Unit;

use App\Factory\CaseFactory;
use App\Filters\CaseFilter;
use App\Models\Account;
use App\Models\Cases;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\CaseRepository;
use App\Requests\SearchRequest;
use App\Transformations\TaskTransformable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CaseTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    /**
     * @var User|Collection|Model|mixed
     */
    private User $user;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Customer|Collection|Model|mixed
     */
    private Customer $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->customer = factory(Customer::class)->create();
    }

    /** @test */
    public function it_can_show_all_the_cases()
    {
        factory(Cases::class)->create();
        $list = (new CaseFilter(new CaseRepository(new Cases())))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
        // $this->assertInstanceOf(Collection::class, $list);
        //$this->assertEquals($insertedtask->title, $myLastElement['title']);
    }

    /** @test */
    public function it_can_delete_the_case()
    {
        $case = factory(Cases::class)->create();
        $taskRepo = new CaseRepository($case);
        $deleted = $taskRepo->newDelete($case);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_case()
    {
        $case = factory(Cases::class)->create();
        $taskRepo = new CaseRepository($case);
        $deleted = $taskRepo->archive($case);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_case()
    {
        $case = factory(Cases::class)->create();
        $data = ['message' => $this->faker->sentence, 'status_id' => 2];
        $caseRepo = new CaseRepository($case);
        $case = $caseRepo->updateCase($data, $case, $case->user);
        $this->assertInstanceOf(Cases::class, $case);
        $this->assertEquals($data['message'], $case->message);
        $this->assertEquals($case->status_id, $data['status_id']);
        $this->assertNotEmpty($case->date_opened);
    }

    /** @test */
    public function it_can_show_the_case()
    {
        $case = factory(Cases::class)->create();
        $caseRepo = new CaseRepository(new Cases);
        $found = $caseRepo->findCaseById($case->id);
        $this->assertInstanceOf(Cases::class, $found);
        $this->assertEquals($case->subject, $found->subject);
    }


    /** @test */
    public function it_can_create_a_case()
    {
        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $this->user->id,
            'customer_id' => $this->customer->id,
            'subject'     => $this->faker->word,
            'message'     => $this->faker->sentence
        ];

        $caseRepo = new CaseRepository(new Cases);
        $factory = (new CaseFactory)->create($this->account, $this->user, $this->customer);
        $case = $caseRepo->createCase($data, $factory);

        $this->assertInstanceOf(Cases::class, $case);
        $this->assertEquals($data['message'], $case->message);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
