<?php

namespace App\FeatureFlags;

use App\FeatureFlags\Repositories\FeatureFlagsEloquentRepository;
use App\User;

class FeatureFlagsManager
{
    /**
     * @var RedisManager
     */
    private $redisManager;

    /**
     * @var FeatureFlagsEloquentRepository
     */
    private $featureFlags;

    /**
     * FeatureFlagsManager constructor.
     *
     * @param RedisManager $redisManager
     * @param FeatureFlagsEloquentRepository $featureFlags
     */
    public function __construct(RedisManager $redisManager, FeatureFlagsEloquentRepository $featureFlags)
    {
        $this->redisManager = $redisManager;
        $this->featureFlags = $featureFlags;
    }

    public function isEnabledFor(string $feature, User $user = null) : bool
    {
        $this->updateRedisCacheIfMissing($feature);

        return $this->redisManager->getCheckerForFlag($feature)
            ->isValidFor($user ?: auth()->user());
    }

    private function updateRedisCacheIfMissing(string $feature)
    {
        $flag = $this->featureFlags->findByFlag($feature);

        if ($flag) {
            $this->redisManager->save($flag);
        }
    }
}