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
    public function it_can_show_all_the_groups()
    {
        factory(GroupSetting::class)->create();
        $list = (new GroupSettingFilter(new GroupSettingRepository(new GroupSetting)))->filter(new SearchRequest(), $this->account);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_group()
    {
        $group_setting = factory(GroupSetting::class)->create();
        $group_setting_repo = new GroupSettingRepository($group_setting);
        $deleted = $group_setting_repo->newDelete($group_setting);
        $this->assertTrue($deleted);
    }

    public function it_can_archive_the_group()
    {
        $group_setting = factory(GroupSetting::class)->create();
        $group_setting_repo = new GroupSettingRepository($group_setting);
        $deleted = $group_setting_repo->archive($group_setting);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_group()
    {
        $group_setting = factory(GroupSetting::class)->create();
        $data = ['name' => $this->faker->word()];
        $group_setting_repo = new GroupSettingRepository($group_setting);
        $updated = $group_setting_repo->save($data, $group_setting);
        $found = $group_setting_repo->findGroupSettingById($group_setting->id);
        $this->assertInstanceOf(GroupSetting::class, $updated);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_show_the_group()
    {
        $group_setting = factory(GroupSetting::class)->create();
        $group_setting_repo = new GroupSettingRepository(new GroupSetting);
        $found = $group_setting_repo->findGroupSettingById($group_setting->id);
        $this->assertInstanceOf(GroupSetting::class, $found);
        $this->assertEquals($group_setting->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_group()
    {
        $user = factory(User::class)->create();
        $factory = (new GroupSettingFactory)->create($this->account, $user);


        $data = [
            'account_id'  => $this->account->id,
            'user_id'     => $user->id,
            'name'        => $this->faker->word()
        ];

        $group_setting_repo = new GroupSettingRepository(new GroupSetting);
        $group_setting = $group_setting_repo->save($data, $factory);
        $this->assertInstanceOf(GroupSetting::class, $group_setting);
        $this->assertEquals($data['name'], $group_setting->name);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}
