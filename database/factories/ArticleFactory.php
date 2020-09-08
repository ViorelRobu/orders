<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'species_id' => 1,
        'quality_id' => $faker->biasedNumberBetween(1, 3, 'sqrt'),
        'product_type_id' => 1,
        'thickness' => $faker->biasedNumberBetween(14, 27, 'sqrt'),
        'width' => $faker->biasedNumberBetween(200, 1250, 'sqrt'),
    ];
});
