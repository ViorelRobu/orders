<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Species;
use Faker\Generator as Faker;

$factory->define(Species::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
