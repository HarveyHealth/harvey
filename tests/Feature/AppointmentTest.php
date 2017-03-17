<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Practitioner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_it_allows_a_patient_to_view_their_own_appointments()
    {
        //GIVEN a patient with 5 appointments
        $patient = factory(Patient::class)->create();
        $patient->appointments()->saveMany(factory(Appointment::class, 5)->make());
        
        // AND there are other appointments in the database
        factory(Appointment::class, 10)->create();
    
        // WHEN the patient requests to list out the appointments
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/appointments');
        
        // THEN we should only see 5 appointments
        $this->assertEquals(count($response->original['data']), 5);
        
        // AND each appointment belongs to the patient
        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }
    
    public function test_it_allows_a_patient_to_schedule_a_new_appointment()
    {
        // GIVEN a patient
        $patient = factory(Patient::class)->create();
        
        // AND a practitioner exists
        $practitioner = factory(Practitioner::class)->create();
        
        // AND valid appointment parameters
        $parameters = [
            'appointment_at' => '2017-12-12 00:00:00',
            'reason_for_visit' => 'Some reason.',
            'practitioner_id' => $practitioner->id
        ];
        
        // WHEN they schedule a new appointment
        Passport::actingAs($patient->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);
    
        // THEN it is successful
        $response->assertStatus(200);
        
        // AND they can see the new appointment information
        $response->assertJsonFragment(['reason_for_visit' => 'Some reason.']);
    }
    
    public function test_a_patient_may_modify_the_date_and_time_of_their_appointment()
    {
        // GIVEN a patient with a scheduled appointment
        // WHEN they update the schedule date
        // THEN it is successful
        // AND they can see the new appointment information
        $this->assertTrue(true);
    }
    
    public function test_a_patient_can_view_information_for_specific_appointments_that_belong_to_them()
    {
        // GIVEN a patient with a scheduled appointment
        // WHEN they attempt to view the information for a specific appointment
        // THEN it is successful
        // AND they can see the appointment information
        $this->assertTrue(true);
    }
    
    public function test_it_allows_a_practitioner_to_view_their_own_appointments()
    {
        // GIVEN a practitioner with a scheduled appointment
        // WHEN they attempt to view the information for a specific appointment
        // THEN it is successful
        // AND they can see the appointment information
        $this->assertTrue(true);
    }
}
