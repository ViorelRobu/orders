<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OrderNumber;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(OrderNumber::class, function (Faker $faker) {
    $now = Carbon::now();
    $new = $now->addMinutes(1);
    return [
        'start_number' => $faker->randomNumber(6),
        'created_at' => $new->toDateTimeString()
    ];
});
