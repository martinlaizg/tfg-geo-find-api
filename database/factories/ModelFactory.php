<?php

use App\User;
use Illuminate\Support\Facades\Hash;

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
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'username' => $faker->unique()->userName,
        'password' => Hash::make($faker->password),
        'bdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'user_type' => $faker->randomElement($array = ['admin', 'creator', 'user'])
    ];
});

$factory->define(App\Map::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->address,
        'country' => $faker->country,
        'state' => $faker->state,
		'city' => $faker->city,
		'creator_id' => User::inRandomOrder()->first()->id,
        'min_level' => $faker->randomElement($array = ['therm', 'compass', 'any'])
    ];
});

$factory->define(App\Location::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->address,
        'lat' => $faker->latitude($min = -90, $max = 90),
        'lon' => $faker->longitude($min = -180, $max = 180)
    ];
});

