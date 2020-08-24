<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => str_replace('.', '', $faker->sentence),
        'author' => $faker->name,
        'description' => $faker->paragraph(6),
        'cover' => "http://picsum.photos/id/{$faker->numberBetween(100, 300)}/200/300?grayscale",
        'price' => $faker->numberBetween(80, 800),
        'pages' => $faker->numberBetween(50, 600),
        'width' => $faker->numberBetween(80, 250),
        'length' => $faker->numberBetween(125, 300),
    ];
});
