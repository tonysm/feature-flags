<?php

namespace App\FeatureFlags;

use App\FeatureFlag;

class ByPassRules
{
    /**
     * @var array
     */
    private $allowedIds;

    /**
     * @param FeatureFlag $featureFlag
     *
     * @return ByPassRules
     */
    public static function fromFeatureFlag(FeatureFlag $featureFlag)
    {
        return new static($featureFlag->bypass_ids ?: []);
    }

    /**
     * ByPassRules constructor.
     *
     * @param array $allowedIds
     */
    public function __construct(array $allowedIds)
    {
        $this->allowedIds = $allowedIds;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function isIdAllowed($id)
    {
        return in_array($id, $this->allowedIds);
    }
}