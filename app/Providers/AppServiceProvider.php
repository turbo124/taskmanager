<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Mail\LeadMailHandler;
use App\Services\Mail\CaseMailHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        DB::listen(function($query) {
//            Log::info(
//                $query->sql,
//                $query->bindings,
//                $query->time
//            );
//        });

        //Mailbox::to('leads@tamtamcrm.com', LeadMailHandler::class);
        //Mailbox::to('{hash}@tamtamcrm.com', CaseMailHandler::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
