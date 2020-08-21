<?php

namespace Tests\Unit;

use App\Factory\CompanyFactory;
use App\Filters\CompanyFilter;
use App\Models\Account;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\User;
use App\Repositories\CompanyContactRepository;
use App\Repositories\CompanyRepository;
use App\Requests\SearchRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyUnitTest extends TestCase
{

    use WithFaker, DatabaseTransactions;

    /**
     * @var Account
     */
    private Account $account;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->user = factory(User::class)->create();
        $this->account = Account::where('id', 1)->first();
    }

    /** @test */
    public function it_can_show_all_the_brands()
    {
        $insertedbrand = factory(Company::class)->create();
        $list = (new CompanyFilter(
            new CompanyRepository(
                new Company,
                new CompanyContactRepository(new CompanyContact)
            )
        ))->filter(new SearchRequest(), $this->account);
        $myLastElement = end($list);
        $this->assertNotEmpty($list);
    }

    /** @test */
    public function it_can_delete_the_brand()
    {
        $brand = factory(Company::class)->create();
        $brandRepo = new CompanyRepository($brand, new CompanyContactRepository(new CompanyContact));
        $deleted = $brandRepo->newDelete($brand);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_archive_the_brand()
    {
        $brand = factory(Company::class)->create();
        $brandRepo = new CompanyRepository($brand, new CompanyContactRepository(new CompanyContact));
        $deleted = $brandRepo->archive($brand);
        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_update_the_brand()
    {
        $brand = factory(Company::class)->create();
        $data = ['name' => $this->faker->company];
        $brandRepo = new CompanyRepository($brand, new CompanyContactRepository(new CompanyContact));
        $updated = $brandRepo->save($data, $brand);
        $found = $brandRepo->findBrandById($brand->id);
        $this->assertInstanceOf(Company::class, $updated);
        $this->assertEquals($data['name'], $found->name);
    }

    /** @test */
    public function it_can_show_the_brand()
    {
        $brand = factory(Company::class)->create();
        $brandRepo = new CompanyRepository(new Company, new CompanyContactRepository(new CompanyContact));
        $found = $brandRepo->findBrandById($brand->id);
        $this->assertInstanceOf(Company::class, $found);
        $this->assertEquals($brand->name, $found->name);
    }

    /** @test */
    public function it_can_create_a_brand()
    {
        $factory = (new CompanyFactory)->create($this->user, $this->account);

        $data = [
            'account_id'   => $this->account->id,
            'user_id'      => $this->user->id,
            'name'         => $this->faker->company,
            'website'      => $this->faker->url,
            'phone_number' => $this->faker->phoneNumber,
            'email'        => $this->faker->email,
            'address_1'    => $this->faker->streetName,
            'address_2'    => $this->faker->streetAddress,
            'town'         => $this->faker->word,
            'city'         => $this->faker->city,
            'country_id'   => 225,
            'postcode'     => $this->faker->postcode
        ];

        $data['contacts'][0]['first_name'] = $this->faker->firstName;
        $data['contacts'][0]['last_name'] = $this->faker->lastName;
        $data['contacts'][0]['phone'] = $this->faker->phoneNumber;
        $data['contacts'][0]['email'] = $this->faker->safeEmail;

        $brandRepo = new CompanyRepository(new Company, new CompanyContactRepository(new CompanyContact));
        $brand = $brandRepo->save($data, $factory);
        $this->assertInstanceOf(Company::class, $brand);
        $this->assertEquals($data['name'], $brand->name);
    }

}
