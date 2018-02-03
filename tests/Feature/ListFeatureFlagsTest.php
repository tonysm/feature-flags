<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\FeatureFlag;
use App\FeatureFlags\FeatureFlags;
use App\FeatureFlags\FeatureFlagsRepository;

class ListFeatureFlagsTest extends TestCase
{
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

    public function testCanListFeatureFlags()
    {
        $flag = new FeatureFlag();
        $flag->id = 123;

        $repository = Mockery::mock(FeatureFlagsRepository::class);
        $repository->shouldReceive('all')
            ->once()
            ->andReturn([$flag]);

        app()->instance(FeatureFlagsRepository::class, $repository);

        $response = $this->getJson('feature-flags/flags');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => 123,
            ],
        ]);
    }
}