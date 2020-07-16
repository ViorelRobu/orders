<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'fibu' => $faker->randomNumber(6),
        'name' => $faker->company,
        'country_id' => 1
    ];
});
