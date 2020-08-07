<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\OrderDetail;
use Faker\Generator as Faker;

$factory->define(OrderDetail::class, function (Faker $faker) {
    $thickness = $faker->biasedNumberBetween(14, 27, 'sqrt');
    $width = $faker->biasedNumberBetween(200, 1250, 'sqrt');
    $length = $faker->biasedNumberBetween(300, 6000, 'sqrt');
    $pcs = $faker->biasedNumberBetween(10, 50, 'sqrt');
    return [
        'order_id' => $faker->biasedNumberBetween(1, 2,'sqrt'),
        'article_id' => $faker->biasedNumberBetween(1, 2,'sqrt'),
        'refinements_list' => '1,2',
        'thickness' => $thickness,
        'width' => $width,
        'length' => $length,
        'pcs' => $pcs,
        'volume' => ($thickness * $width * $length * $pcs) / 1000000000,
        'produced_ticom' => 1,
        'batch' => $faker->biasedNumberBetween(1, 50, 'sqrt'),
        'produced_batch' => 1,
        'loading_date' => $faker->date(),
        'details_json' => 'test'
    ];
});
