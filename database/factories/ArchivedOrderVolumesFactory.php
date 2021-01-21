<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ArchivedOrderVolume;
use Faker\Generator as Faker;

$factory->define(ArchivedOrderVolume::class, function (Faker $faker) {
    return [
        'order_id' => 1,
        'order_volume' => 0,
        'delivered_volume' => 0
    ];
});
