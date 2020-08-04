<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order' => $faker->biasedNumberBetween(2000000, 2999999, 'sqrt'),
        'customer_id' => $faker->biasedNumberBetween(1, 3, 'sqrt'),
        'customer_order' => $faker->word(),
        'auftrag' => $faker->randomNumber(),
        'destination_id' => $faker->biasedNumberBetween(1, 3, 'sqrt'),
        'customer_kw' => $faker->date(),
        'production_kw' => $faker->date(),
        'delivery_kw' => $faker->date(),
        'loading_date' => $faker->date(),
        'eta' => $faker->date(),
        'priority' => $faker->biasedNumberBetween(1, 10, 'sqrt'),
        'observations' => $faker->paragraph(),
    ];
});
