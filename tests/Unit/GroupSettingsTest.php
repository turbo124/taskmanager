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

class GroupSettingsTest extends TestCase
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
    public function it_can_show_all_the_projects()
    {
        $insertedproject = factory(GroupSetting::class)->create();
        $group_settings_repo = new GroupSettingRepository(new GroupSetting);
        $list =  $group_settings_repo->listProjects()->toArray();
        $myLastElement = end($list);
        // $this->assertInstanceOf(Collection::class, $list);
        $this->assertEquals($insertedproject->toArray(), $myLastElement);
    }

    /** @test */
    public function it_can_delete_the_project()
    {
        $project = factory(Project::class)->create();
        $group_settings_repo = new ProjectRepository($project);
        $deleted = $group_settings_repo->newDelete($project);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_project()
    {
        $project = factory(Project::class)->create();
        $title = $this->faker->word;
        $data = ['title' => $title];
        $group_settings_repo = new ProjectRepository($project);
        $updated = $group_settings_repo->save($data, $project);
        $found = $group_settings_repo->findProjectById($project->id);
        $this->assertInstanceOf(Project::class, $updated);
        $this->assertEquals($data['title'], $found->title);
    }

    /** @test */
    public function it_can_show_the_project()
    {
        $project = factory(Project::class)->create();
        $group_settings_repo = new ProjectRepository(new Project);
        $found = $group_settings_repo->findProjectById($project->id);
        $this->assertInstanceOf(Project::class, $found);
        $this->assertEquals($project->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_project()
    {
        $data = [
            'name'        => $this->faker->word,
        ];

        $group_settings_repo = new ProjectRepository(new Project);
        $factory = (new GroupSettingFactory())->create($this->user, $this->customer, $this->account);
        $project = $group_settings_repo->save($data, $factory);
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals($data['title'], $project->title);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
