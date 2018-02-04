<?php

namespace App\FeatureFlags\Checkers;

use App\User;

class FeatureDisabled implements Checker
{
    public function isValidFor(User $user = null)
    {
        return false;
    }
}