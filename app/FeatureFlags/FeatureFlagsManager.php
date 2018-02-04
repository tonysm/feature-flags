<?php

namespace App\FeatureFlags;

use App\FeatureFlag;
use Illuminate\Contracts\Redis\Factory;

class FeatureFlagsManager
{
    /**
     * @var Factory
     */
    private $redisFactory;

    /**
     * FeatureFlagsManager constructor.
     *
     * @param Factory $redisFactory
     */
    public function __construct(Factory $redisFactory)
    {
        $this->redisFactory = $redisFactory;
    }

    public function save(FeatureFlag $flag)
    {
        $redis = $this->connection();

        $redis->hmset($flag->flag, [
            'id' => $flag->id,
            'value' => $flag->value,
        ]);

        $this->updateRules($flag);
    }

    public function updateRules(FeatureFlag $flag)
    {
        if (!empty($flag->bypass_ids)) {
            $this->connection()
                ->hmset($flag->flag .':allowed_ids', $flag->bypass_ids ?: []);
        } else {
            $this->connection()->del($flag->flag. ':allowed_ids');
        }
    }

    /**
     * @return \Illuminate\Redis\Connections\Connection
     */
    private function connection()
    {
        return $this->redisFactory->connection('feature-flags');
    }
}