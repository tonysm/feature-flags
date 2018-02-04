<?php

namespace Tests\Feature;

use App\Events\FlagWasDisabled;
use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\FeatureFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureFlagStatusTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        FeatureFlags::authUsing(function () {
            return true;
        });
    }

    public function tearDown()
    {
        parent::tearDown();

        FeatureFlags::$auth = null;
    }

    public function testCanDisableActiveFeatureFlags()
    {
        $this->withoutExceptionHandling();
        Event::fake();
        $featureFlag = factory(FeatureFlag::class)->create([
            'value' => true,
        ]);

        $response = $this->postJson(route('disabled-flags.store'), [
            'feature_flag_id' => $featureFlag->getKey(),
            'confirmation' => '1',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'id' => $featureFlag->getKey(),
            'value' => false,
        ]);

        $this->assertFalse($featureFlag->fresh()->value);
        Event::assertDispatched(FlagWasDisabled::class, function (FlagWasDisabled $event) use ($featureFlag) {
            return $event->flag->is($featureFlag);
        });
    }
}