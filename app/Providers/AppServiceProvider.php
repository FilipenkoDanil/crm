<?php

namespace App\Providers;

use App\Contracts\Payment;
use App\Services\v1\LiqPay;
use App\Services\v1\WayForPay;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Payment::class, function () {
            return new LiqPay();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
