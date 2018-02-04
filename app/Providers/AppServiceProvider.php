<?php

namespace App\Providers;

use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FeatureFlags::routes();

//        FeatureFlags::authUsing(function ($request) {
//            return true;
//        });
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
