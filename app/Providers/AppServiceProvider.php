<?php

namespace App\Providers;

use App\Models\CloudServer;
use App\Models\MinecraftServer;
use App\Observers\CloudServersObserver;
use App\Observers\MinecraftServerObserver;
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
        //
        MinecraftServer::observe(MinecraftServerObserver::class);
        CloudServer::observe(CloudServersObserver::class);
    }
}
