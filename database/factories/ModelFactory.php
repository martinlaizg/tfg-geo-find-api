<?php

use App\Map;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('es_ES');
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'username' => $faker->unique()->userName,
        'password' => $faker->password,
        'image' => $faker->imageUrl($width = 800, $height = 400, 'people'),
        'bdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'user_type' => $faker->randomElement($array = ['admin', 'creator', 'user']),
    ];
});

$factory->define(App\Map::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('es_ES');
    return [
        'name' => $faker->address,
        'country' => $faker->country,
        'state' => $faker->state,
        'city' => $faker->city,
        'image' => $faker->imageUrl($width = 800, $height = 400, 'nature'),
        'creator_id' => User::inRandomOrder()->first()->id,
        'min_level' => $faker->randomElement($array = ['therm', 'compass', 'any']),
    ];
});

$factory->define(App\Location::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('es_ES');
    return [
        'name' => $faker->address,
        'map_id' => Map::inRandomOrder()->first()->id,
        'lat' => $faker->latitude($min = -90, $max = 90),
        'lon' => $faker->longitude($min = -180, $max = 180),
        'image' => $faker->imageUrl($width = 800, $height = 400, 'transport'),
    ];
});
