<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        //require_once __DIR__ . '/DatesSeeder.php';
        
        $this->call(EventTypeSeeder::class);
        //$this->call(DesignSeeder::class);
        //$this->call(DateFormatSeeder::class);
        //$this->call(PaymentTypeSeeder::class);
        //$this->call(IndustrySeeder::class);
        //$this->call(FrequencySeeder::class);
        //$this->call(PaymentStatusSeeder::class);
        //factory(App\User::class, 1)->create();
        /*$this->call(leadColumns::class); */
        //$this->call(CurrenciesSeeder::class);
        //$this->call(CountriesTableSeeder::class);
        /*$this->call(TaskSeeder::class); */
    }

}
