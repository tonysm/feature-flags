<?php

namespace App;

use App\FeatureFlags\Checkers;
use App\FeatureFlags\ByPassRules;
use App\FeatureFlags\Checkers\Checker;
use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $guarded = [];

    protected $casts = [
        'bypass_ids' => 'array',
        'value' => 'boolean',
    ];

    public function checker() : Checkers\Checker
    {
        if ($this->value) {
            return new Checkers\FeatureEnabled();
        }

        if ($this->bypass_ids) {
            return new Checkers\FeatureByPass(ByPassRules::fromFeatureFlag($this));
        }

        return new Checkers\FeatureDisabled();
    }
}
