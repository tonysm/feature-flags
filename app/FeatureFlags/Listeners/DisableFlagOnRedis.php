<?php

namespace App\FeatureFlags\Listeners;

use App\Events\FlagWasDisabled;
use App\FeatureFlags\RedisManager;

class DisableFlagOnRedis
{
    /**
     * @var RedisManager
     */
    private $redisManager;

    /**
     * DisableFlagOnRedis constructor.
     *
     * @param RedisManager $redisManager
     */
    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    public function handle(FlagWasDisabled $event)
    {
        $this->redisManager->save($event->flag);
    }
}