<?php

namespace App\FeatureFlags\Checkers;

use App\User;

class FeatureEnabled implements Checker
{
    /**
     * @param User|null $user
     *
     * @return bool
     */
    public function isValidFor(User $user = null)
    {
        return true;
    }
}