<?php

$factory->define(Ingenious\Store\Models\FakeResource::class, function (Faker\Generator $faker) {
    return [
        'xlink:href' => $faker->url,
        'mediaType' => "png",
        'type' => "preview",
    ];

});
