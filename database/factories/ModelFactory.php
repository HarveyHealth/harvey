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
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
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
        'remember_token' => str_random(10)
    ];
});

$factory->define(App\Models\Patient::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'user_id' => factory(App\Models\User::class)->create()->id,
        'birthdate' => $faker->dateTimeBetween($startDate = '-80 years', $endDate = '-20 years'),
        'height_feet' => $faker->numberBetween(4, 6),
        'height_inches' => $faker->numberBetween(0, 12),
        'weight' => $faker->numberBetween(100, 300),
        'symptoms' => json_encode([
                $faker->word => $faker->numberBetween(1, 10),
                $faker->word => $faker->numberBetween(1, 10),
                $faker->word => $faker->numberBetween(1, 10)
            ])
    ];
});

$factory->define(App\Models\Practitioner::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'user_id' => factory(App\Models\User::class)->create()->id,
        'practitioner_type' => 1,
    ];
});

$factory->define(App\Models\PractitionerType::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'name' => $faker->name,
        'rate' => $faker->numberBetween(100, 300)
    ];
});

$factory->define(App\Models\Admin::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'user_id' => factory(App\Models\User::class)->create()->id,
    ];
});


$factory->define(App\Models\SKU::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'item_type' => $faker->randomElement(['test', 'product', 'service']),
        'price' => $faker->randomNumber(2),
    ];
});

$factory->define(App\Models\Appointment::class, function (Faker\Generator $faker) {

    $start_time = Carbon::instance($faker->dateTimeBetween($startDate = 'now', $endDate = '+7 days', 'UTC'));

    return [
        'patient_id' => factory(App\Models\Patient::class)->create()->id,
        'practitioner_id' => factory(App\Models\Practitioner::class)->create()->id,
        'appointment_at' => $start_time->toDateTimeString(),
        'appointment_block_ends_at' => $start_time->addMinutes(90)->toDateTimeString(),
        'reason_for_visit' => $faker->sentence
    ];
});

$factory->state(App\Models\Appointment::class, 'past', function ($faker) {
    $start_time = Carbon::instance($faker->dateTimeBetween($startDate = '-3 days', $endDate = '-1 days', 'UTC'));
    return ['appointment_at' => $start_time->toDateTimeString(), 'appointment_block_ends_at' => $start_time->addMinutes(90)];
});

$factory->define(App\Models\Test::class, function (Faker\Generator $faker) {
    return [
        'patient_id' => factory(App\Models\Patient::class)->create()->id,
        'practitioner_id' => factory(App\Models\Practitioner::class)->create()->id,
        'sku_id' => factory(App\Models\SKU::class)->create()->id
    ];
});

$factory->define(App\Models\PatientNote::class, function (Faker\Generator $faker) {
    return [
        'patient_id' => factory(App\Models\Patient::class)->create()->id,
        'practitioner_id' => factory(App\Models\Practitioner::class)->create()->id,
        'appointment_id' => factory(App\Models\Appointment::class)->create()->id,
        'note' => $faker->sentence
    ];
});
