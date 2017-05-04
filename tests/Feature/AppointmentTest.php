<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Practitioner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use \ResponseCode;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_allows_a_patient_to_view_their_own_appointments()
    {
        // Given a patient with 5 appointments
        $patient = factory(Patient::class)->create();
        $patient->appointments()->saveMany(factory(Appointment::class, 5)->make());

        // And there are other appointments in the database
        factory(Appointment::class, 10)->create();

        // When the patient requests to list out the appointments
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/appointments');

        // Then we should only see 5 appointments
        $this->assertEquals(count($response->original['data']), 5);

        // And each appointment belongs to the patient
        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }

    public function test_it_allows_a_patient_to_schedule_a_new_appointment()
    {
        // Given a patient
        $patient = factory(Patient::class)->create();

        // And a practitioner exists
        $practitioner = factory(Practitioner::class)->create();

        // And valid appointment parameters
        $parameters = [
            'appointment_at' => '2017-12-12 00:00:00',
            'reason_for_visit' => 'Some reason.',
            'practitioner_id' => $practitioner->id
        ];

        // When they schedule a new appointment
        Passport::actingAs($patient->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the new appointment information
        $response->assertJsonFragment(['reason_for_visit' => 'Some reason.']);
    }

    public function test_a_patient_may_modify_the_date_and_time_of_their_appointment()
    {
        // Given a patient with a scheduled appointment
        $appointment = factory(Appointment::class)->create();
        $patient = $appointment->patient;

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => '2017-12-12 00:00:00',
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they update the appointment
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the new appointment information
        $this->assertDatabaseHas('appointments', ['appointment_at' => '2017-12-12 00:00:00']);
        $this->assertDatabaseHas('appointments', ['reason_for_visit' => 'Some new reason.']);
    }

    public function test_it_does_not_allow_modifications_if_the_appointment_is_less_than_2_hours_away()
    {
        // Given a patient with a scheduled appointment less than 2 hours away
        $appointment = factory(Appointment::class)->states('soon')->create();
        $patient = $appointment->patient;

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => '2017-12-12 00:00:00',
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they attempt to update the schedule date
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        // And they get an error response message
        $response->assertSee('You are unable to modify an appointment with less than 2 hours of notice.');
    }

    public function test_it_does_not_allow_cancellation_if_the_appointment_is_less_than_2_hours_away()
    {
        // Given a patient with a scheduled appointment less than 2 hours away
        $appointment = factory(Appointment::class)->states('soon')->create();
        $patient = $appointment->patient;

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => '2017-12-12 00:00:00',
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they try deleting the schedule
        Passport::actingAs($patient->user);
        $response = $this->json('DELETE', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        // And they get an error response message
        $response->assertSee('You are unable to cancel an appointment with less than 2 hours of notice.');
    }

    public function test_it_does_not_allow_modification_if_the_appointment_was_completed()
    {
        // Given a patient with an appointment in the past
        $appointment = factory(Appointment::class)->states('past')->create();
        $patient = $appointment->patient;

        // And new parameters they want to save to the appointment
        $parameters = [
            'reason_for_visit' => 'Some other reason.',
        ];

        // When they try modifying the appointment
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        // And they get an error response message
        $response->assertSee('error');
    }

    public function test_a_patient_can_view_information_for_specific_appointments_that_belong_to_them()
    {
        // Given a patient with a scheduled appointment
        $appointment = factory(Appointment::class)->create([
            'reason_for_visit' => 'some reason.'
        ]);
        $patient = $appointment->patient;

        // When they attempt to view the information for a specific appointment
        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/appointments/{$appointment->id}");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the appointment information
        $response->assertJsonFragment(['reason_for_visit' => 'some reason.']);
    }

    public function test_it_allows_a_practitioner_to_view_their_own_appointments()
    {
        // Given a practitioner with a scheduled appointment
        $appointment = factory(Appointment::class)->create([
            'reason_for_visit' => 'some reason.'
        ]);
        $practitioner = $appointment->practitioner;

        // When they attempt to view the information for a specific appointment
        Passport::actingAs($practitioner->user);
        $response = $this->json('GET', "api/v1/appointments");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the appointment information
        $response->assertJsonFragment(['reason_for_visit' => 'some reason.']);
    }

    public function test_it_can_include_patient_and_user_information()
    {
        // Given a practitioner with a scheduled appointment
        $appointment = factory(Appointment::class)->create([
            'reason_for_visit' => 'some reason.'
        ]);
        $admin = factory(Admin::class)->create();

        // When they attempt to view the information for a specific appointment
        // and include patient and user info
        Passport::actingAs($admin->user);
        $response = $this->json('GET', "api/v1/appointments/{$appointment->id}?include=patient.user");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the appointment information
        $response->assertJsonFragment(['reason_for_visit' => 'some reason.']);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
                'links',
                'relationships'
            ],
            'included' => [
                '*' => [
                    'type',
                    'id',
                    'attributes',
                    'links',
                ],
            ]
        ]);
    }
}
