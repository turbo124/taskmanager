<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Role;
use App\User;
use App\Repositories\RoleRepository;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Factory\RoleFactory;

class RoleTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    private $account_id = 1;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_can_list_all_roles()
    {
        factory(Role::class, 5)->create();
        $roleRepo = new RoleRepository(new Role);
        $roles = $roleRepo->listRoles();
        $this->assertInstanceOf(Collection::class, $roles);
    }

    /** @test */
    public function it_can_delete_the_role()
    {
        $role = factory(Role::class)->create();
        $roleRepo = new RoleRepository($role);
        $deleted = $roleRepo->deleteRoleById();
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_role()
    {
        $role = factory(Role::class)->create();
        $data = [
            'name' => 'user'
        ];
        $roleRepo = new RoleRepository($role);
        $updated = $roleRepo->save($data, $role);
        $role = $roleRepo->findRoleById($role->id);
        $this->assertInstanceOf(Role::class, $updated);
        $this->assertEquals($data['name'], $role->name);
    }

    /** @test */
    public function it_can_return_the_created_role()
    {
        $roleFactory = factory(Role::class)->create();
        $roleRepo = new RoleRepository(new Role);
        $role = $roleRepo->findRoleById($roleFactory->id);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals($roleFactory->name, $role->name);
    }

    /** @test */
    public function it_can_create_a_role()
    {
	    $factory = (new RoleFactory)->create($this->account_id, $this->user->id);

        $data = [
            'name' => 'user',
        ];
        $roleRepo = new RoleRepository(new Role);
        $role = $roleRepo->save($data, $factory);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals($data['name'], $role->name);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
