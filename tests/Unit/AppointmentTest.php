<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Practitioner;
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
        $appointment = factory(Appointment::class)->states('past')->make();
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
    
    public function test_it_displays_appointment_date_info_for_patient_or_practitioner_timezone()
    {
        // Given a practitioner on the east coast
        $practitioner = factory(Practitioner::class)->create();
        $practitioner_user = $practitioner->user;
        $practitioner_user->timezone = 'America/New_York';
        $practitioner_user->save();
    
        // and a patient on the west coast
        $patient = factory(Patient::class)->create();
        $patient_user = $patient->user;
        $patient_user->timezone = 'America/Los_Angeles';
        $patient_user->save();
        
        // and an appointment based in utc
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'patient_id' => $patient->id,
            'appointment_at' => '2017-01-01 00:00:00'
        ]);
        
        // We can get the appointment at based in the patient timezone
        $this->assertEquals('2016-12-31 16:00:00', $appointment->patientAppointmentAtDate()->format('Y-m-d H:i:s'));
        
        // We can get the appointment at based in the practitioner timezone
        $this->assertEquals('2016-12-31 19:00:00', $appointment->practitionerAppointmentAtDate()->format('Y-m-d H:i:s'));
    }
}
