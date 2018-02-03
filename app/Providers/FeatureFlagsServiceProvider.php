<?php

namespace App\Providers;

use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\ServiceProvider;

class FeatureFlagsServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        FeatureFlags::routes();

//        FeatureFlags::authUsing(function ($request) {
//            return true;
//        });
    }
}