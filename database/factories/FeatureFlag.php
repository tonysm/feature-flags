<?php

use Faker\Generator as Faker;

$factory->define(App\FeatureFlag::class, function (Faker $faker) {
    return [
        'flag' => strtoupper($faker->word),
        'value' => $faker->boolean,
        'description' => $faker->sentence,
    ];
});

$factory->state(App\FeatureFlag::class, 'disabled', function () {
    return [
        'value' => false,
    ];
});
