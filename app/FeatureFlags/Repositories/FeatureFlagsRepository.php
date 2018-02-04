<?php

namespace App\FeatureFlags\Repositories;

use App\FeatureFlag;
use Illuminate\Database\Eloquent\Collection;

interface FeatureFlagsRepository
{
    public function save(FeatureFlag $featureFlag);
    public function all() : Collection;
    public function update(FeatureFlag $featureFlag, array $data);
}