<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_gets_the_next_upcoming_appointment()
    {
        // Given a user with multiple upcoming appointments
        $patient = factory(Patient::class)->create();

        $patient->appointments()->saveMany([
            $appt = factory(Appointment::class)->states('soon')->make(),
            factory(Appointment::class)->make(),
            factory(Appointment::class)->make()
        ]);

        // When we call the 'next upcoming appointment' method
        $next_appt = $patient->user->nextUpcomingAppointment();

        // It returns only the next appointment
        $this->assertEquals($appt->id, $next_appt->id);
    }

    public function test_it_has_an_upcoming_appointment()
    {
        $patient = factory(Patient::class)->create();

        $this->assertFalse($patient->user->hasUpcomingAppointment());

        $patient->appointments()->saveMany([
            factory(Appointment::class)->make()
        ]);

        $this->assertTrue($patient->user->hasUpcomingAppointment());
    }

    public function test_if_an_exception_is_raised_when_user_has_no_type()
    {
        $user = factory(User::class)->create();

        $this->expectException(\Exception::class);
        $user->type;
    }
}
