<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Budget;
use Faker\Generator as Faker;

$factory->define(Budget::class, function (Faker $faker) {
    return [
        'product_type_id' => 1,
        'year' => $faker->year(),
        'week' => 5,
        'volume' => $faker->randomFloat(1,4)
    ];
});
