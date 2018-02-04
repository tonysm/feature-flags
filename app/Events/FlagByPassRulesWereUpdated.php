<?php

namespace App\Events;

use App\FeatureFlag;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class FlagByPassRulesWereUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * @var FeatureFlag
     */
    public $flag;

    /**
     * Create a new event instance.
     *
     * @param FeatureFlag $flag
     */
    public function __construct(FeatureFlag $flag)
    {
        $this->flag = $flag;
    }
}
