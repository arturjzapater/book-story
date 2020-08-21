<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CartProduct;
use Faker\Generator as Faker;

$factory->define(CartProduct::class, function (Faker $faker) {
    return [
        'cart_id' => 1,
        'product_id' => $faker->unique()->numberBetween(1, 6),
    ];
});
