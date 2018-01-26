<?php

namespace Tests\Unit;

use App\Models\{Appointment, Practitioner, PractitionerSchedule};
use App\Lib\{PractitionerAvailability, TimeInterval};
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use DateTime;

class PractitionerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_displays_an_empty_result_if_no_availability_set_without_predefined_blocks()
    {
        $practitioner = factory(Practitioner::class)->create();

        $this->assertEquals(collect([]), $practitioner->availability);
    }

    public function test_it_shows_the_correct_availability_if_schedules_are_set_without_predefined_blocks()
    {
        $knownDate = Carbon::create(2017, 4, 17, 12);
        Carbon::setTestNow($knownDate);

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-19T15:00:00+00:00',
            '2017-04-19T15:30:00+00:00',
            '2017-04-19T16:00:00+00:00',
            '2017-04-26T15:00:00+00:00',
            '2017-04-26T15:30:00+00:00',
            '2017-04-26T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $practitioner_availability = (new PractitionerAvailability($practitioner, TimeInterval::days(2)->toHours(), $only_predefined_blocks = false))->availabilityAsCollection();

        $this->assertEquals($expected_result, $practitioner_availability);
        Carbon::setTestNow(Carbon::now());
    }

    public function test_it_shows_the_correct_availability_if_schedules_are_set_and_using_48hs_buffer_without_predefined_blocks()
    {
        Carbon::setTestNow(Carbon::create(2017, 4, 18, 12));

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-26T15:00:00+00:00',
            '2017-04-26T15:30:00+00:00',
            '2017-04-26T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $practitioner_availability = (new PractitionerAvailability($practitioner, TimeInterval::days(2)->toHours(), $only_predefined_blocks = false))->availabilityAsCollection();

        $this->assertEquals($expected_result, $practitioner_availability);
        Carbon::setTestNow(Carbon::now());
    }

    public function test_it_will_not_show_availability_if_appointment_overlaps_without_predefined_blocks()
    {
        Carbon::setTestNow(Carbon::create(2017, 4, 17, 12));

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
                'day_of_week' => 'Wednesday',
                'start_time' => '08:00:00',
                'stop_time' => '10:00:00'
            ])
        );

        $overlap = Carbon::parse('next week wednesday');
        $overlap->setTime(15, 0, 0);
        $practitioner->appointments()->save(
            factory(Appointment::class)->make([
                'appointment_at' => $overlap,
                'status' => 'pending',
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-19T15:00:00+00:00',
            '2017-04-19T15:30:00+00:00',
            '2017-04-19T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $practitioner_availability = (new PractitionerAvailability($practitioner, TimeInterval::days(2)->toHours(), $only_predefined_blocks = false))->availabilityAsCollection();

        $this->assertEquals($expected_result, $practitioner_availability);
        Carbon::setTestNow(Carbon::now());
    }

    public function test_it_shows_the_correct_availability_if_schedules_are_set()
    {
        $knownDate = Carbon::create(2017, 4, 17, 12);
        Carbon::setTestNow($knownDate);

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-19T15:00:00+00:00',
            '2017-04-19T15:30:00+00:00',
            '2017-04-19T16:00:00+00:00',
            '2017-04-26T15:00:00+00:00',
            '2017-04-26T15:30:00+00:00',
            '2017-04-26T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $this->assertEquals($expected_result, $practitioner->availability);
        Carbon::setTestNow(Carbon::now());
    }

    public function test_it_shows_the_correct_availability_if_schedules_are_set_and_using_48hs_buffer()
    {
        Carbon::setTestNow(Carbon::create(2017, 4, 18, 12));

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-26T15:00:00+00:00',
            '2017-04-26T15:30:00+00:00',
            '2017-04-26T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $this->assertEquals($expected_result, $practitioner->availability);
        Carbon::setTestNow(Carbon::now());
    }

    public function test_it_will_not_show_availability_if_appointment_overlaps()
    {
        Carbon::setTestNow(Carbon::create(2017, 4, 17, 12));

        $practitioner = factory(Practitioner::class)->create();

        $practitioner->schedule()->save(
            factory(PractitionerSchedule::class)->make([
                'day_of_week' => 'Wednesday',
                'start_time' => '08:00:00',
                'stop_time' => '10:00:00'
            ])
        );

        $overlap = Carbon::parse('next week wednesday');
        $overlap->setTime(15, 0, 0);
        $practitioner->appointments()->save(
            factory(Appointment::class)->make([
                'appointment_at' => $overlap,
                'status' => 'pending',
            ])
        );

        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();

        $expected_result = collect([
            '2017-04-19T15:00:00+00:00',
            '2017-04-19T15:30:00+00:00',
            '2017-04-19T16:00:00+00:00',
            '2017-05-03T15:00:00+00:00',
            '2017-05-03T15:30:00+00:00',
            '2017-05-03T16:00:00+00:00',
            '2017-05-10T15:00:00+00:00',
            '2017-05-10T15:30:00+00:00',
            '2017-05-10T16:00:00+00:00',
        ]);

        $this->assertEquals($expected_result, $practitioner->availability);
        Carbon::setTestNow(Carbon::now());
    }

    protected function tearDown()
    {
        Carbon::setTestNow(Carbon::parse((new DateTime('now'))->format('c')));
        parent::tearDown();
    }
}
