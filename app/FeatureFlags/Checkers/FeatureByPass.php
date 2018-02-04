<?php

namespace App\FeatureFlags\Checkers;

use App\User;
use App\FeatureFlags\ByPassRules;

class FeatureByPass implements Checker
{
    /**
     * @var ByPassRules
     */
    private $byPassRules;

    /**
     * FeatureByPass constructor.
     *
     * @param ByPassRules $byPassRules
     */
    public function __construct(ByPassRules $byPassRules)
    {
        $this->byPassRules = $byPassRules;
    }

    public function isValidFor(User $user = null)
    {
        return $this->byPassRules->isIdAllowed($user->getKey());
    }
}