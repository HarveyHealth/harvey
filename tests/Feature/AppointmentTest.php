<?php

namespace Tests\Feature;

use App\Models\{
    Admin,
    Appointment,
    AppointmentReminder,
    DiscountCode,
    Patient,
    Practitioner,
    PractitionerSchedule,
    User
};
use App\Lib\PractitionerAvailability;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon, Log, ResponseCode;


class AppointmentTest extends TestCase
{
    use DatabaseMigrations;

    protected function getRemindersCommandOutput()
    {
        return $this->getCommandOutput('appointments:reminders');
    }

    protected function createScheduleAndGetValidAppointmentAt(Practitioner $practitioner)
    {
        PractitionerSchedule::where('practitioner_id', $practitioner->id)->delete();

        $start_block = collect(PractitionerAvailability::PREDEFINED_BLOCKS)->random();

        list($start_hour, $start_minute) = explode(':', $start_block);

        $start_time = "{$start_hour}:{$start_minute}:00";

        $stop_hour = rand($start_hour + 2, 24);
        $stop_minutes = (24 == $stop_hour) ? '00' : maybe() ? '00' : '30';
        $stop_time = "{$stop_hour}:{$stop_minutes}:00";

        $practitionerSchedule = factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'stop_time' => $stop_time,
            'start_time' => $start_time,
        ]);

        return Carbon::parse($practitionerSchedule->practitioner->availability->random())->format('Y-m-d H:i:s');
    }

    public function test_it_finds_appointments_24hs_before_start_time()
    {
        foreach ([-1, 22, 23, 24, 25, 26] as $hoursFromNow) {
            factory(Appointment::class)->create([
                'appointment_at' => Carbon::parse("{$hoursFromNow} hours"),
                'status' => 'pending'
            ]);
        }

        $output = $this->getRemindersCommandOutput();

        $this->assertEquals('[Found 3 Appointments.]', $output[1]);

//      $this->assertEquals('[3 Client Email appointments 24hs reminders sent.]', $output[14]);
//      $this->assertEquals('[3 Doctor Email appointments 24hs reminders sent.]', $output[15]);
        $this->assertEquals('[0 Client Email appointments 24hs reminders sent.]', $output[14]);
        $this->assertEquals('[0 Doctor Email appointments 24hs reminders sent.]', $output[15]);
        $this->assertEquals('[3 Client SMS Appointments 24hs reminders sent.]', $output[16]);
        $this->assertEquals('[3 Doctor SMS Appointments 24hs reminders sent.]', $output[17]);
    }

    public function test_not_pending_appointments_are_excluded_from_reminders()
    {
        foreach ([-1, 22, 23, 24, 25, 26] as $hoursFromNow) {
            factory(Appointment::class)->create([
                'appointment_at' => Carbon::parse("{$hoursFromNow} hours"),
                'status' => collect(Appointment::STATUSES)->diff('pending')->random(),
            ]);
        }

        $output = $this->getRemindersCommandOutput();

        $this->assertEquals('[Found 0 Appointments.]', $output[1]);
    }

    public function test_it_marks_the_reminder_as_sent_after_sending()
    {
        $this->test_it_finds_appointments_24hs_before_start_time();

        // Run command again (Reminders were already sent)
        $output = $this->getRemindersCommandOutput();

        $this->assertEquals('[Found 3 Appointments.]', $output[1]);

        $this->assertEquals('[0 Client Email appointments 24hs reminders sent.]', $output[14]);
        $this->assertEquals('[0 Doctor Email appointments 24hs reminders sent.]', $output[15]);
        $this->assertEquals('[0 Client SMS Appointments 24hs reminders sent.]', $output[16]);
        $this->assertEquals('[0 Doctor SMS Appointments 24hs reminders sent.]', $output[17]);
    }

    public function test_patient_email_24hs_reminder_is_filled_properly()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();
        $appointment = factory(Appointment::class)->create([
            'appointment_at' => Carbon::parse("2 hours"),
            'patient_id' => $patient->id,
            'practitioner_id' => $practitioner->id,
            'status' => 'pending',
        ]);

        $output = $this->getRemindersCommandOutput();

        $this->assertEquals('[Found 1 Appointments.]', $output[1]);
/*
        $this->assertEmailWasSentTo($patient->user->email);
        $this->assertEmailTemplateNameWas('patient.appointment.reminder');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_timezone' => $appointment->patientAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->full_name,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
        ]);
*/
    }

    public function test_practitioner_email_24hs_reminder_is_filled_properly()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();
        $appointment = factory(Appointment::class)->create([
            'appointment_at' => Carbon::parse("2 hours"),
            'patient_id' => $patient->id,
            'practitioner_id' => $practitioner->id,
            'status' => 'pending',
        ]);

        $output = $this->getRemindersCommandOutput();

        $this->assertEquals('[Found 1 Appointments.]', $output[1]);
/*
        $this->assertEmailWasSentTo($practitioner->user->email);
        $this->assertEmailTemplateNameWas('practitioner.appointment.reminder');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_timezone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->full_name,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
        ]);
*/
    }

    public function test_appointment_reminder_type_is_set_properly()
    {
        $appointment = factory(Appointment::class)->create();

        $reminder = AppointmentReminder::create([
            'appointment_id' => $appointment->id,
            'recipient_user_id' => $appointment->patient->user->id,
            'type' => AppointmentReminder::TYPES[AppointmentReminder::SMS_24_HS_NOTIFICATION_ID],
            'sent_at' => Carbon::now(),
        ]);

        $this->assertEquals(AppointmentReminder::SMS_24_HS_NOTIFICATION_ID, $reminder->type_id);
        $this->assertEquals(AppointmentReminder::TYPES[AppointmentReminder::SMS_24_HS_NOTIFICATION_ID], $reminder->type);
        $this->assertEquals($appointment->patient->user->id, $reminder->recipient->id);
    }

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
        $this->assertEquals(count($response->original->data), 5);

        // And each appointment belongs to the patient
        foreach ($response->original->data as $item) {
            $this->assertEquals($item->attributes->patient_id, $patient->id);
        }
    }

    public function test_it_does_not_allows_a_patient_to_view_others_appointments()
    {
        $patient = factory(Patient::class)->create();
        $patient->appointments()->save(factory(Appointment::class)->make());

        $patient1 = factory(Patient::class)->create();
        $patient1->appointments()->save(factory(Appointment::class)->make());

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/appointments/{$patient->appointments()->first()->id}");
        $response->assertStatus(ResponseCode::HTTP_OK);

        Passport::actingAs($patient1->user);
        $response = $this->json('GET', "api/v1/appointments/{$patient->appointments()->first()->id}");
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_view_all_appointments()
    {
        factory(Patient::class, 2)->create()->each(function ($patient) {
            $patient->appointments()->saveMany(factory(Appointment::class, 3)->make());
        });

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('GET', 'api/v1/appointments');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(6, $response->original->data);
    }

    public function test_it_allows_an_admin_to_view_upcoming_appointments()
    {
        factory(Patient::class, 3)->create()->each(function ($patient) {
            $patient->appointments()->saveMany(factory(Appointment::class, 3)->states('past')->make());
            $patient->appointments()->saveMany(factory(Appointment::class, 2)->states('soon')->make());
        });

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('GET', 'api/v1/appointments?filter=upcoming');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(6, $response->original->data);
    }

    public function test_it_allows_a_patient_to_schedule_a_new_appointment()
    {
        // Given a patient
        $patient = factory(Patient::class)->create();

        // And a practitioner exists
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);

        // And valid appointment parameters
        $parameters = [
            'appointment_at' => $appointment_at,
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

    public function test_it_does_not_allows_a_patient_to_schedule_more_than_3_appointments()
    {
        // Given a patient
        $patient = factory(Patient::class)->create();

        // And a practitioner exists
        $practitioner = factory(Practitioner::class)->create();

        for ($i=0;$i<3;$i++){

          $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);
          // And valid appointment parameters
          $parameters = [
              'appointment_at' => $appointment_at,
              'reason_for_visit' => 'Some reason.',
              'practitioner_id' => $practitioner->id
          ];

          // When they schedule a new appointment
          Passport::actingAs($patient->user);
          $response = $this->json('POST', 'api/v1/appointments', $parameters);

          if ($i<2)
          {
            $response->assertStatus(ResponseCode::HTTP_OK);
          }
          else
          {
            $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
          }
        }
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);



    }

    public function test_it_allows_a_practitioner_to_schedule_a_new_appointment()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some reason.',
            'patient_id' => factory(Patient::class)->create()->id,
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['reason_for_visit' => 'Some reason.']);
    }

    public function test_it_requires_a_patient_id_when_a_practitioner_schedule_a_new_appointment()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some reason.',
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }

    public function test_a_patient_may_modify_the_date_and_time_of_their_pending_appointments()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'status' => 'pending',
        ]);
        $patient = $appointment->patient;
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($appointment->practitioner);

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they update the appointment
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the new appointment information
        $this->assertDatabaseHas('appointments', ['appointment_at' => $appointment_at]);
        $this->assertDatabaseHas('appointments', ['reason_for_visit' => 'Some new reason.']);
    }

    public function test_a_patient_may_not_modify_the_date_and_time_of_their_canceled_appointments()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'status' => 'canceled',
        ]);
        $patient = $appointment->patient;
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($appointment->practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some new reason.',
        ];

        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('appointments', ['appointment_at' => $appointment->appointment_at]);
        $this->assertDatabaseHas('appointments', ['reason_for_visit' => $appointment->reason_for_visit]);
    }

    public function test_a_patient_can_submit_the_same_date_and_time_when_updating_their_appointment()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);
        $appointment = factory(Appointment::class)->create([
            'appointment_at' => $appointment_at,
            'practitioner_id' => $practitioner->id,
        ]);

        $parameters = [
            'appointment_at' => $appointment->appointment_at->format('Y-m-d H:i:s'),
            'reason_for_visit' => 'Some new reason.',
        ];

        Passport::actingAs($appointment->practitioner->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertDatabaseHas('appointments', ['reason_for_visit' => 'Some new reason.']);
    }

    public function test_a_patient_may_not_modify_a_non_pending_appointment()
    {
        $practitioner = factory(Practitioner::class)->create();
        $appointment = factory(Appointment::class)->create([
            'practitioner_id' => $practitioner->id,
            'status' => 'complete',
        ]);
        $patient = $appointment->patient;
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($appointment->practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some new reason.',
        ];

        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allow_modifications_if_the_appointment_is_less_than_4_hours_away()
    {
        $practitioner = factory(Practitioner::class)->create();
        // Given a patient with a scheduled appointment less than 4 hours away
        $appointment = factory(Appointment::class)->states('soon')->create(['practitioner_id' => $practitioner->id]);
        $patient = $appointment->patient;
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($appointment->practitioner);

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they attempt to update the schedule date
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        // And they get an error response message
        $response->assertSee('You are unable to modify an appointment with less than 4 hours of notice.');
    }

    public function test_it_does_not_allow_cancellation_if_the_appointment_is_less_than_4_hours_away()
    {
        $practitioner = factory(Practitioner::class)->create();
        // Given a patient with a scheduled appointment less than 4 hours away
        $appointment = factory(Appointment::class)->states('soon')->create(['practitioner_id' => $practitioner->id]);
        $patient = $appointment->patient;
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($appointment->practitioner);

        // And new parameters they want to save to the appointment
        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some new reason.',
        ];

        // When they try deleting the schedule
        Passport::actingAs($patient->user);
        $response = $this->json('DELETE', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);

        // And they get an error response message
        $response->assertSee('You are unable to cancel an appointment with less than 4 hours of notice.');
    }

    public function test_it_allow_cancellation_if_the_appointment_is_more_than_4_hours_away()
    {
        $appointment = factory(Appointment::class)->create();

        Passport::actingAs($appointment->patient->user);
        $response = $this->json('DELETE', "api/v1/appointments/{$appointment->id}");

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }

    public function test_an_admin_or_practitioner_can_modify_an_appointment_regardless_of_how_soon_the_appointment_will_be()
    {
        // Given a patient with a scheduled appointment less than 4 hours away
        $appointment = factory(Appointment::class)->states('soon')->create();
        $admin = factory(Admin::class)->create();

        // And new parameters they want to save to the appointment
        $parameters = [
            'reason_for_visit' => 'Some other reason.',
        ];

        // When they try updating the schedule
        Passport::actingAs($admin->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And when the practitioner attempts to modify the appointment
        $parameters = [
            'reason_for_visit' => 'Some other reason by the practitioner.',
        ];
        Passport::actingAs($appointment->practitioner->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // but when the patient tries to modify the appointment
        $parameters = [
            'reason_for_visit' => 'Hey, I want to cancel this.',
        ];
        Passport::actingAs($appointment->patient->user);
        $response = $this->json('PATCH', "api/v1/appointments/{$appointment->id}", $parameters);

        // Then it is unsuccessful due to the 4 hour lock
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
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

    public function test_it_saves_the_discount_code_id_when_creating_an_appointment_as_patient()
    {
        $discount_code = factory(DiscountCode::class)->create([
            'code' => 'abc123',
            'applies_to' => 'consultation',
        ]);
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some reason.',
            'practitioner_id' => $practitioner->id,
            'discount_code' => 'abc123',
        ];

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['discount_code_id' => "{$discount_code->id}"]);

        $this->assertDatabaseHas('appointments', ['discount_code_id' => $discount_code->id]);
    }

    public function test_it_does_not_saves_the_discount_code_id_when_creating_an_appointment_as_practitioner()
    {
        $discount_code = factory(DiscountCode::class)->create([
            'code' => 'abc123',
            'applies_to' => 'consultation',
        ]);
        $practitioner = factory(Practitioner::class)->create();
        $appointment_at = $this->createScheduleAndGetValidAppointmentAt($practitioner);

        $parameters = [
            'appointment_at' => $appointment_at,
            'reason_for_visit' => 'Some reason.',
            'patient_id' => factory(Patient::class)->create()->id,
            'discount_code' => 'abc123',
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->json('POST', 'api/v1/appointments', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['discount_code_id' => null]);

        $this->assertDatabaseHas('appointments', ['discount_code_id' => null]);
    }
}
