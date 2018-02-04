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
        return FeatureFlag::all();
    }

    public function update(FeatureFlag $featureFlag, array $data)
    {
        $featureFlag->update($data);
    }
}