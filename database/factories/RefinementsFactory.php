<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Refinement;
use Faker\Generator as Faker;

$factory->define(Refinement::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence(6)
    ];
});
