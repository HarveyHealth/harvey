<?php

namespace Tests\Unit;

use App\Models\Appointment;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_it_locks_an_appointment_when_less_than_2_hours_away()
    {
        $an_hour_away = Carbon::now()->addHour();
        $appointment = factory(Appointment::class)->make([
            'appointment_at' => $an_hour_away
        ]);
        
        $this->assertTrue($appointment->isLocked());
    }
    
    public function test_an_appointment_is_locked_when_it_is_in_the_past()
    {
        $hours_away = Carbon::now()->subHour();
        $appointment = factory(Appointment::class)->make([
            'appointment_at' => $hours_away
        ]);
        
        $this->assertTrue($appointment->isLocked());
    }
    
    public function test_an_appointment_is_not_locked_when_over_2_hours_away()
    {
        $hours_away = Carbon::now()->addHours(5);
        $appointment = factory(Appointment::class)->make([
            'appointment_at' => $hours_away
        ]);
        
        $this->assertTrue($appointment->isNotLocked());
    }
}
