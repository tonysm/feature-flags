<?php

namespace App\FeatureFlags\Listeners;

use App\FeatureFlags\RedisManager;
use App\Events\FlagByPassRulesWereUpdated;

class UpdateFlagByPassRules
{
    /**
     * @var RedisManager
     */
    private $redisManager;

    /**
     * UpdateFlagByPassRules constructor.
     *
     * @param RedisManager $redisManager
     */
    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    /**
     * @param FlagByPassRulesWereUpdated $event
     */
    public function handle(FlagByPassRulesWereUpdated $event)
    {
        $this->redisManager->updateRules($event->flag);
    }
}