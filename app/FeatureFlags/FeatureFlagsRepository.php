<?php

namespace App\FeatureFlags;

use App\FeatureFlag;

class FeatureFlagsRepository
{
    public function save(FeatureFlag $featureFlag)
    {
        $featureFlag->save();
    }

    public function all()
    {
        return FeatureFlags::all();
    }
}