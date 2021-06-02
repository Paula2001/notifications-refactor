<?php

namespace App\Providers;

use App\Services\MobileNotifications\ThirdParty\ClientInterface;
use App\Services\MobileNotifications\ThirdParty\ClientOne\ClientOne;
use App\Services\MobileNotifications\ThirdParty\ClientTwo\ClientTwo;
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
        $this->app->bind(ClientInterface::class, function ($app) {
            return (env('KIND_OF_CLIENT') == 'one') ?  new ClientOne() : new ClientTwo();
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
