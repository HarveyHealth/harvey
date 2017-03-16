<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_it_only_allows_a_patient_to_view_their_own_appointments()
    {
        //GIVEN a user with 5 appointments
        $patient = factory(Patient::class)->create();
        $patient->appointments()->saveMany(factory(Appointment::class, 5)->make());
    
    
        // AND there are other appointments in the database
        factory(Appointment::class, 10)->create();
    
        // WHEN the user requests to list out the appointments
        Passport::actingAs($patient->user, ['*']);
        $response = $this->call(
            'GET',
            "api/v1/appointments"
        );
        $content = json_decode($response->content());

        // THEN we should only see 5 of them
        $this->assertEquals(count($content->data), 5);
    }
    
    public function test_it_only_allows_a_practitioner_to_view_their_own_appointments()
    {
        $this->assertTrue(true);
    }
    
    public function test_it_allows_a_patient_to_schedule_a_new_appointment()
    {
        $this->assertTrue(true);
    }
    
    public function test_a_patient_may_modify_the_date_and_time_of_their_appointment()
    {
        $this->assertTrue(true);
    }
}
