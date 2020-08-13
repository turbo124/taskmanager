<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Factory\GroupFactory;
use App\Factory\ProjectFactory;
use App\Filters\GroupFilter;
use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Requests\SearchRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\Account;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class GroupTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    /**
     * @var \App\Models\Account
     */
    private Account $account;

    /**
     * @var \App\Models\User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private User $user;

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
        factory(Group::class)->create();
        $list = (new GroupFilter(new GroupRepository(new Group)))->filter(
            new SearchRequest,
            $this->account
        );
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_group()
    {
        $group_setting = factory(Group::class)->create();
        $group_setting_repo = new GroupRepository($group_setting);
        $deleted = $group_setting_repo->newDelete($group_setting);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_group()
    {
        $group_setting = factory(Group::class)->create();
        $group_setting_repo = new GroupRepository($group_setting);
        $deleted = $group_setting_repo->archive($group_setting);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_group()
    {
        $group_setting = factory(Group::class)->create();
        $data = ['name' => $this->faker->word()];
        $group_setting_repo = new GroupRepository($group_setting);
        $updated = $group_setting_repo->save($data, $group_setting);
        $found = $group_setting_repo->findGroupById($group_setting->id);
        $this->assertInstanceOf(Group::class, $updated);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_show_the_group()
    {
        $group_setting = factory(Group::class)->create();
        $group_setting_repo = new GroupRepository(new Group);
        $found = $group_setting_repo->findGroupById($group_setting->id);
        $this->assertInstanceOf(Group::class, $found);
        $this->assertEquals($group_setting->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_group()
    {
        $user = factory(User::class)->create();
        $factory = (new GroupFactory())->create($this->account, $user);


        $data = [
            'account_id' => $this->account->id,
            'user_id'    => $user->id,
            'name'       => $this->faker->word()
        ];

        $group_setting_repo = new GroupRepository(new Group);
        $group_setting = $group_setting_repo->save($data, $factory);
        $this->assertInstanceOf(Group::class, $group_setting);
        $this->assertEquals($data['name'], $group_setting->name);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
