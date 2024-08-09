<?php

namespace App\Providers;

use App\Services\FlashCard\CacheService;
use App\Services\FlashCard\FlashCardService;
use App\Services\FlashCard\SessionService;
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
        $this->app->singleton(FlashCardService::class);
        $this->app->singleton(SessionService::class);
        $this->app->singleton(CacheService::class);
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
