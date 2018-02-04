<?php

namespace App\FeatureFlags\Listeners;

use App\Events\FlagWasCreated;
use App\FeatureFlags\RedisManager;

class StoreFlagOnRedisWhenCreated
{
    /**
     * @var RedisManager
     */
    private $redisManager;

    /**
     * StoreFlagOnRedisWhenCreated constructor.
     *
     * @param RedisManager $redisManager
     */
    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    /**
     * @param FlagWasCreated $event
     */
    public function handle(FlagWasCreated $event)
    {
        $this->redisManager->save($event->featureFlag);
    }
}