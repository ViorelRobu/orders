<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\OrderNumber;
use Faker\Generator as Faker;

$factory->define(OrderNumber::class, function (Faker $faker) {
    return [
        'start_number' => $faker->randomNumber(6)
    ];
});
