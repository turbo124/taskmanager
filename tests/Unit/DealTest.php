<?php

namespace Tests\Unit;

use App\Factory\DealFactory;
use App\Filters\DealFilter;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Deal;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\DealRepository;
use App\Requests\SearchRequest;
use App\Transformations\DealTransformable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DealTest extends TestCase
{

    use DatabaseTransactions, WithFaker, TaskTransformable;

    private $user;
    private $customer;
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
        $this->customer = factory(Customer::class)->create();
    }

    /** @test */
    public function it_can_show_all_the_tasks()
    {
        $insertedtask = factory(Deal::class)->create();
        $list = (new DealFilter(
            new DealRepository(
                new Deal
            )
        ))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
        //$this->assertInstanceOf(Deal::class, $list[0]);
        // $this->assertInstanceOf(Collection::class, $list);
        //$this->assertEquals($insertedtask->title, $myLastElement['title']);
    }

    /** @test */
    public function it_can_delete_the_task()
    {
        $deal = factory(Deal::class)->create();
        $dealRepo = new DealRepository($deal);
        $deleted = $dealRepo->newDelete($deal);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_task()
    {
        $deal = factory(Deal::class)->create();
        $dealRepo = new DealRepository($deal);
        $deleted = $dealRepo->archive($deal);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_task()
    {
        $deal = factory(Deal::class)->create();
        $title = $this->faker->word;
        $data = ['title' => $title];
        $dealRepo = new DealRepository($deal);
        $deal = $dealRepo->save($data, $deal);
        $found = $dealRepo->findDealById($deal->id);
        $this->assertInstanceOf(Deal::class, $deal);
        $this->assertEquals($data['title'], $found->title);
    }

    /** @test */
    public function it_can_show_the_task()
    {
        $deal = factory(Deal::class)->create();
        $dealRepo = new DealRepository(new Deal);
        $found = $dealRepo->findDealById($deal->id);
        $this->assertInstanceOf(Deal::class, $found);
        $this->assertEquals($deal->title, $found->title);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $data = [
            'account_id'   => $this->account->id,
            'task_status'  => 1,
            'customer_id'  => $this->customer->id,
            'title'        => $this->faker->word,
            'description'  => $this->faker->sentence,
            'is_completed' => 0,
            'due_date'     => $this->faker->dateTime,
        ];

        $dealRepo = new DealRepository(new Deal);
        $factory = (new DealFactory())->create($this->user, $this->account);
        $deal = $dealRepo->save($data, $factory);
        $this->assertInstanceOf(Deal::class, $deal);
        $this->assertEquals($data['title'], $deal->title);
    }

   

    /** @test */
    public function it_errors_finding_a_deal()
    {
        $this->expectException(ModelNotFoundException::class);
        $task = new DealRepository(new Deal);
        $task->findDealById(999);
    }

    /** @test */
    public function it_can_transform_task()
    {
        $customer = factory(Customer::class)->create();

        $title = $this->faker->title;
        $description = $this->faker->sentence;
        $due_date = $this->faker->dateTime;
        $task_type = 2;

        $address = factory(Deal::class)->create(
            [
                'account_id' => $this->account->id,
                'title'      => $title,
                'description' => $description,
                'due_date'   => $due_date
               
            ]
        );

        $transformed = $this->transformDeal($address);
        $this->assertNotEmpty($transformed);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
