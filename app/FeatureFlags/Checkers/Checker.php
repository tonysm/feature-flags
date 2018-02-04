<?php

namespace App\FeatureFlags\Checkers;

use App\User;

interface Checker
{
    public function isValidFor(User $user = null);
}