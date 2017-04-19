<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Practitioner;
use App\Models\PractitionerSchedule;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PractitionerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_it_displays_an_empty_result_if_no_availability_set()
    {
        $practitioner = factory(Practitioner::class)->create();

        $this->assertEquals(['week 1' => [], 'week 2' => []], $practitioner->availability());
    }
    
    public function test_it_shows_the_correct_availability_if_schedules_are_set()
    {
        $knownDate = Carbon::create(2017, 4, 17, 12); // create testing date
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

        $expected_result = [
            'week 1' => [
                'Wednesday 15:00',
                'Wednesday 15:30',
                'Wednesday 16:00'
            ],
            'week 2' => [
                'Wednesday 15:00',
                'Wednesday 15:30',
                'Wednesday 16:00'
            ]
        ];
        
        $this->assertEquals($expected_result, $practitioner->availability());
        Carbon::setTestNow(Carbon::now());
    }
    
    public function test_it_will_not_show_availability_if_appointment_overlaps()
    {
        $knownDate = Carbon::create(2017, 4, 17, 12); // create testing date
        Carbon::setTestNow($knownDate);
        
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
                'appointment_at' => $overlap
            ])
        );
        
        $user = $practitioner->user;
        $user->timezone = 'America/Los_Angeles';
        $user->save();
        $practitioner->save();
        
        $expected_result = [
            'week 1' => [
                'Wednesday 15:00',
                'Wednesday 15:30',
                'Wednesday 16:00'
            ],
            'week 2' => []
        ];
        
        $this->assertEquals($expected_result, $practitioner->availability());
        Carbon::setTestNow(Carbon::now());
    }
}
