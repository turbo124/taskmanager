<?php

namespace Tests\Unit;

use App\Models\Cases;
use App\Models\Customer;
use App\Events\Lead\LeadWasCreated;
use App\Factory\CaseFactory;
use App\Factory\LeadFactory;
use App\Filters\CaseFilter;
use App\Filters\LeadFilter;
use App\Models\Lead;
use App\Repositories\CaseRepository;
use App\Repositories\LeadRepository;
use App\Repositories\ProjectRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformations\TaskTransformable;

class CaseTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    /**
     * @var \App\Models\User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private User $user;

    /**
     * @var \App\Models\Account
     */
    private Account $account;

    /**
     * @var \App\Models\Customer|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
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
        $data = ['message' => $this->faker->sentence];
        $caseRepo = new CaseRepository($case);
        $task = $caseRepo->save($data, $case);
        $found = $caseRepo->findCaseById($case->id);
        $this->assertInstanceOf(Cases::class, $case);
        $this->assertEquals($data['message'], $found->message);
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
        $case = $caseRepo->save($data, $factory);

        $this->assertInstanceOf(Cases::class, $case);
        $this->assertEquals($data['message'], $case->message);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
