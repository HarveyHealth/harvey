<?php

use Carbon\Carbon;

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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'enabled' => true,
        'user_type' => $faker->randomElement(['admin', 'patient', 'practitioner']),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'enabled' => true,
        'password' => $password ?: $password = bcrypt('secret'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => Carbon::now(),
        'phone' => $faker->randomElement(array('626','323','818')) . $faker->numberBetween(1111111, 9999999),
        'phone_verified_at' => Carbon::now(),
        'address_1' => $faker->buildingNumber . ' ' . $faker->streetName,
        'city' => $faker->city,
        'state' => 'CA',
        'zip' => $faker->postcode,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'timezone' => $faker->timezone,
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\Models\User::class, 'admin', function ($faker) {
    return ['user_type' => 'admin'];
});

$factory->state(App\Models\User::class, 'patient', function ($faker) {
    return ['user_type' => 'patient'];
});

$factory->state(App\Models\User::class, 'practitioner', function ($faker) {
    return ['user_type' => 'practitioner'];
});

$factory->define(App\Models\SKU::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'item_type' => $faker->randomElement(['test', 'product', 'service']),
        'price' => $faker->randomNumber(2),
    ];
});

$factory->define(App\Models\Appointment::class, function (Faker\Generator $faker) {
    return [
        'patient_user_id' => factory(App\Models\User::class)->states('patient')->create()->id,
        'practitioner_user_id' => factory(App\Models\User::class)->states('practitioner')->create()->id,
        'appointment_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+7 days'),
        'reason_for_visit' => $faker->sentence
    ];
});

$factory->state(App\Models\Appointment::class, 'past', function ($faker) {
    return ['appointment_at' => $faker->dateTimeBetween($startDate = '-3 days', $endDate = '-1 days'),];
});

$factory->define(App\Models\Test::class, function (Faker\Generator $faker) {
    return [
        'patient_user_id' => factory(App\Models\User::class)->states('patient')->create()->id,
        'practitioner_user_id' => factory(App\Models\User::class)->states('practitioner')->create()->id,
        'sku_id' => factory(App\Models\SKU::class)->create()->id
    ];
});

$factory->define(App\Models\PatientNote::class, function (Faker\Generator $faker) {
    return [
        'patient_user_id' => factory(App\Models\User::class)->states('patient')->create()->id,
        'practitioner_user_id' => factory(App\Models\User::class)->states('practitioner')->create()->id,
        'appointment_id' => factory(App\Models\Appointment::class)->create()->id,
        'note' => $faker->paragraph
    ];
});
