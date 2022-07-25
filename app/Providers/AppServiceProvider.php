<?php

namespace App\Providers;

use App\Services\Contracts\ResourceService;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ResourceService::class, function () {
            return new \App\Services\ResourceService();
        });
    }
}
