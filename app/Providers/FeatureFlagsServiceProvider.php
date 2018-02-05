<?php

namespace App\Providers;

use App\FeatureFlags\Events;
use App\FeatureFlags\Listeners;
use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\FeatureFlags\FeatureFlagsManager;

class FeatureFlagsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->configure();
        $this->registerFlagsManager();
    }

    private function configure()
    {
        FeatureFlags::use(config('feature-flags.use'));
    }

    private function registerFlagsManager()
    {
        $this->app->alias(FeatureFlagsManager::class, 'feature-flags');
    }

    public function boot()
    {
        $this->registerEventListeners();
    }

    private function registerEventListeners()
    {
        $events = [
            Events\FlagWasCreated::class => Listeners\StoreFlagOnRedisWhenCreated::class,
            Events\FlagWasDisabled::class => Listeners\DisableFlagOnRedis::class,
            Events\FlagWasReEnabled::class => Listeners\ReEnableFlagOnRedis::class,
            Events\FlagByPassRulesWereUpdated::class => Listeners\UpdateFlagByPassRules::class,
        ];

        foreach ($events as $event => $listener) {
            Event::listen($event, $listener);
        }
    }
}