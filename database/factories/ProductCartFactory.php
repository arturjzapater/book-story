<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductCart;
use Faker\Generator as Faker;

$factory->define(ProductCart::class, function (Faker $faker) {
    return [
        'cart_id' => 1,
        'product_id' => $faker->numberBetween(1, 6),
    ];
});
