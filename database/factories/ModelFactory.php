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
        'phone' => $faker->randomElement(['626','323','818']) . $faker->numberBetween(1111111, 9999999),
        'phone_verified_at' => Carbon::now(),
        'address_1' => $faker->buildingNumber . ' ' . $faker->streetName,
        'city' => $faker->city,
        'state' => 'CA',
        'zip' => $faker->postcode,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'timezone' => $faker->randomElement(['America/Juneau', 'America/Los_Angeles', 'America/Chicago', 'America/New_York']),
        'remember_token' => str_random(10)
    ];
});

$factory->define(App\Models\Patient::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
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
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'license_id'  => function () {
            return factory(App\Models\License::class)->create()->id;
        },
        'practitioner_type' => factory(App\Models\PractitionerType::class)->create()->id,
        'specialty' => json_encode([$faker->word, $faker->jobTitle]),
        'description' => $faker->text,
        'school' => "{$faker->word} {$faker->word} {$faker->word}",
        'graduated_at' => Carbon::parse('-5 year')->hour(0)->minute(0)->second(0),
    ];
});

$factory->define(App\Models\PractitionerType::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'name' => $faker->jobTitle,
        'rate' => $faker->numberBetween(100, 300)
    ];
});

$factory->define(App\Models\PractitionerSchedule::class, function (Faker\Generator $faker) {
    $workableDays = collect();

    for ($i = 0; $i < 5; $i++) {
        $workableDays->push(Carbon::parse('next Monday')->addDay($i)->format('l'));
    }

    $start_hour = rand(0, 22);
    $start_time = "{$start_hour}:{$faker->randomElement([0, 30])}:00";

    $stop_hour = rand($start_hour + 2, 24);
    $stop_minutes = (24 == $stop_hour) ? '00' : $faker->randomElement([0, 30]);
    $stop_time = "{$stop_hour}:{$stop_minutes}:00";

    return [
        'practitioner_id' => function () {
            return factory(App\Models\Practitioner::class)->create()->id;
        },
        'day_of_week' => $workableDays->random(),
        'start_time' => $start_time,
        'stop_time' => $stop_time,
    ];
});

$factory->define(App\Models\Admin::class, function (Faker\Generator $faker) {
    return [
        'enabled' => true,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Models\SKU::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'item_type' => $faker->randomElement(['test', 'product', 'service']),
        'price' => $faker->randomNumber(2),
    ];
});

$factory->define(App\Models\License::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->regexify('[A-Z]{2}-[0-9]{4}'),
        'state' => $faker->randomElement(['CA', 'NV', 'AZ']),
    ];
});

$factory->define(App\Models\Appointment::class, function (Faker\Generator $faker) {
    // Create an appointment start time that begins at the top of the hour
    // or 30 minutes into the hour
    $start_time = Carbon::instance(
        $faker->dateTimeBetween($startDate = '+1 days', $endDate = '+5 days')
    );
    $start_time->minute = $faker->randomElement([0, 30]);
    $start_time->second = 0;

    return [
        'duration_in_minutes' => $faker->randomElement([null, 30, 60]);
        'patient_id' => function () {
            return factory(App\Models\Patient::class)->create()->id;
        },
        'practitioner_id' => function () {
            return factory(App\Models\Practitioner::class)->create()->id;
        },
        'appointment_at' => $start_time->toDateTimeString(),
        'reason_for_visit' => $faker->sentence,
    ];
});

$factory->state(App\Models\Appointment::class, 'past', function ($faker) {
    $start_time = Carbon::instance($faker->dateTimeBetween(
        $startDate = '-3 days', $endDate = '-1 days', 'UTC')
    );
    $start_time->minute = $faker->randomElement([0, 30]);
    $start_time->second = 0;

    return ['appointment_at' => $start_time->toDateTimeString()];
});

$factory->state(App\Models\Appointment::class, 'soon', function ($faker) {
    return ['appointment_at' => Carbon::now()->addMinutes(30)];
});

$factory->define(App\Models\Test::class, function (Faker\Generator $faker) {
    return [
        'patient_id' => factory(App\Models\Patient::class)->create()->id,
        'practitioner_id' => factory(App\Models\Practitioner::class)->create()->id,
        'sku_id' => function () {
            return factory(App\Models\SKU::class)->create()->id;
        },
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

$factory->define(Laravel\Passport\Client::class, function (Faker\Generator $faker) {
    return [
        'secret' => str_random(40),
        'redirect' => 'http://localhost',
        'name' => $faker->word,
        'personal_access_client' => 0,
        'password_client' => 1,
        'revoked' => 0
    ];
});

$factory->define(App\Models\Message::class, function (Faker\Generator $faker) {
    $classesNames = collect([
        App\Models\Practitioner::class,
        App\Models\Patient::class,
        App\Models\Admin::class,
    ]);
    $classesNames = $classesNames->shuffle();

    $senderClassName = $classesNames->pop();
    $output['sender_user_id'] = function () use ($senderClassName) {
        return factory($senderClassName)->create()->user->id;
    };

    $recipientClassName = $classesNames->pop();
    $output['recipient_user_id'] = function () use ($recipientClassName) {
        return factory($recipientClassName)->create()->user->id;
    };
    $output['message'] = $faker->text;
    $output['subject'] = $faker->sentence;
    $output['is_admin'] = App\Models\Admin::class == $senderClassName;
    $output['read_at'] = rand(0,1) ? null : Carbon::parse('+ 10 seconds');

    return $output;
});

$factory->define(App\Models\LabOrder::class, function (Faker\Generator $faker) {
    return [
        'patient_id' => function () {
            return factory(App\Models\Patient::class)->create()->id;
        },
        'practitioner_id' => function () {
            return factory(App\Models\Practitioner::class)->create()->id;
        },
        'shipment_code' => $faker->isbn13,
        'address_1' => $faker->buildingNumber . ' ' . $faker->streetName,
        'city' => $faker->city,
        'state' => 'CA',
        'zip' => $faker->postcode,
    ];
});

$factory->define(App\Models\LabTest::class, function (Faker\Generator $faker) {
    return [
        'lab_order_id' => function () {
            return factory(App\Models\LabOrder::class)->create()->id;
        },
        'sku_id' => function () {
            return factory(App\Models\SKU::class)->create()->id;
        },
        'shipment_code' => $faker->isbn13,
    ];
});
