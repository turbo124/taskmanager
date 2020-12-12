<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LeadMailHandler;
use App\Services\CaseMailHandler;

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
        //Mailbox::to('cases@tamtamcrm.com', CaseMailHandler::class);
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
