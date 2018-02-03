<?php

namespace Tests\Feature;

use App\Events\FlagWasCreated;
use App\FeatureFlags\FeatureFlags;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\FeatureFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFeatureFlagsTest extends TestCase
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

    public function testCanCreateFeatureFlags()
    {
        Event::fake();

        $response = $this->postJson('/feature-flags/flags', [
            'flag' => 'SOMETHING',
            'description' => 'Lorem Ipsum',
            'value' => false,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'flag' => 'SOMETHING',
            'description' => 'Lorem Ipsum',
            'value' => false,
        ]);

        $this->assertCount(1, FeatureFlag::where([
            'flag' => 'SOMETHING',
            'description' => 'Lorem Ipsum',
            'value' => false,
        ])->get());

        Event::assertDispatched(FlagWasCreated::class, function (FlagWasCreated $event) {
            return $event->featureFlag->is(FeatureFlag::first());
        });
    }

    public function testFailsValidationWhenFlagNameIsMissing()
    {
        $response = $this->postJson('/feature-flags/flags', [
            'description' => 'Lorem Ipsum',
            'value' => false,
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, FeatureFlag::all());
    }

    public function testValidatesFlagValueIsRequired()
    {
        $response = $this->postJson('/feature-flags/flags', [
            'flag' => 'SOMETHING',
            'description' => 'Lorem Ipsum',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, FeatureFlag::all());
    }

    public function testValidatesFlagValueIsBoolean()
    {
        $response = $this->postJson('/feature-flags/flags', [
            'flag' => 'SOMETHING',
            'description' => 'Lorem Ipsum',
            'value' => 'lorem',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, FeatureFlag::all());
    }
}
