<?php

namespace App\FeatureFlags\Listeners;

use App\Events\FlagWasReEnabled;
use App\FeatureFlags\RedisManager;

class ReEnableFlagOnRedis
{
    /**
     * @var RedisManager
     */
    private $redisManager;

    /**
     * ReEnableFlagOnRedis constructor.
     *
     * @param RedisManager $redisManager
     */
    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    public function handle(FlagWasReEnabled $event)
    {
        $this->redisManager->save($event->flag);
    }
}