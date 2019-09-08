<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'id_type' => $faker->randomElement([User::CC_TYPE, User::TI_TYPE]),
        'id_num' => $faker->unique()->randomNumber(),
        //'email_verified_at' => now(),
        'password' => $password?:$password = bcrypt('secret') ,
        'verified' => Str::random(40),
    ];
});
