<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //$this->call(RoleTableSeeder::class);
        //(new Permissions())->create();
        $this->call(CurrenciesTableSeeder::class);
//        $this->call(LanguagesSeeder::class);
//        $this->call(IndustryTableSeeder::class);
//        $this->call(CountriesTableSeeder::class);
    }

}
