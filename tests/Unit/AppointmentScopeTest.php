<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Practitioner;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AppointmentScopeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_only_returns_appointments_with_enabled_practitioners()
    {
        $practitioner = factory(Practitioner::class)->create();
        $patient = factory(Patient::class)->create();
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'patient_id' => $patient->id,
            'appointment_at' => '2017-01-01 00:00:00'
        ]);

        $this->assertCount(1, Appointment::all());

        $practitioner->user->enabled = false;
        $practitioner->user->save();

        $this->assertCount(0, Appointment::all());    	
    }

    public function test_it_only_returns_appointments_with_enabled_patients()
    {
        $practitioner = factory(Practitioner::class)->create();
        $patient = factory(Patient::class)->create();
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'patient_id' => $patient->id,
            'appointment_at' => '2017-01-01 00:00:00'
        ]);

        $this->assertCount(1, Appointment::all());

        $patient->user->enabled = false;
        $patient->user->save();

        $this->assertCount(0, Appointment::all());      
    }
}
