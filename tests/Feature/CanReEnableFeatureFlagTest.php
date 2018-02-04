<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\FeatureFlag;
use App\Events\FlagWasReEnabled;
use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanReEnableFeatureFlagTest extends TestCase
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

    public function testCanEnableFeatureFlag()
    {
        Event::fake();
        $featureFlag = factory(FeatureFlag::class)->states(['disabled'])->create();

        $response = $this->postJson(route('enabled-flags.store'), [
            'feature_flag_id' => $featureFlag->getKey(),
            'confirmed' => '1',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'id' => $featureFlag->getKey(),
            'value' => true,
        ]);

        $this->assertTrue($featureFlag->refresh()->value);
        Event::assertDispatched(FlagWasReEnabled::class, function (FlagWasReEnabled $event) use ($featureFlag) {
            return $event->flag->is($featureFlag);
        });
    }
}