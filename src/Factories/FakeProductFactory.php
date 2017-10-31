<?php

$factory->define(Ingenious\Store\Models\FakeProduct::class, function (Faker\Generator $faker) {
    return [
        'xlink:href' => $faker->url,
        'id' => $faker->randomDigit,
        'name' => $faker->word,
        'version' => $faker->randomDigit,
        'resources' => factory("Ingenious\Store\Models\FakeResource",3)->make(),
    ];

});


