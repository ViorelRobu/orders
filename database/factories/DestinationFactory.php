<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Destination;
use Faker\Generator as Faker;

$factory->define(Destination::class, function (Faker $faker) {
    return [
        'customer_id' => 1,
        'country_id' => 1,
        'address' => $faker->address
    ];
});
