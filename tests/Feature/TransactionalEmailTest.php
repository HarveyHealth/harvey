<?php

namespace Tests\Feature;

use App\Events\{AppointmentCanceled, AppointmentScheduled, AppointmentUpdated, UserRegistered};
use App\Jobs\SendTransactionalEmail;
use App\Models\{Appointment,Patient,User, LabTest, LabTestInformation};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Log;

class TransactionalEmailTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_error_are_raised_if_data_is_valid()
    {
        Log::spy();

        dispatch(new SendTransactionalEmail('valid@email.com', 'patient.welcome', ['data' => 'valid']));

        Log::shouldNotHaveReceived(['error', 'warning']);
    }

    public function test_an_error_is_raised_if_to_value_is_empty()
    {
        Log::spy();

        dispatch(new SendTransactionalEmail('', 'patient.welcome', ['data' => 'valid']));

        Log::shouldHaveReceived('error')->once();
    }

    public function test_an_error_is_raised_if_template_value_is_wrong()
    {
        Log::spy();

        dispatch(new SendTransactionalEmail('valid@email.com', 'pat-ient.welcome', ['data' => 'valid']));

        Log::shouldHaveReceived('error')->once();
    }

    public function test_a_warning_is_raised_if_some_data_value_is_empty()
    {
        Log::spy();

        $data = [
            'data' => 'valid',
            'moreData' => '',
            "evenMoreData" => 124,
        ];

        dispatch(new SendTransactionalEmail('valid@email.com', 'patient.welcome', $data));

        Log::shouldHaveReceived('warning')->once();
    }

    public function test_user_registered_event_fills_correctly_welcome_email()
    {
        $patient = factory(Patient::class)->create();

        event(new UserRegistered($patient->user));

        $this->assertEmailWasSentTo($patient->user->email);
        $this->assertEmailTemplateNameWas('patient.welcome');
        $this->assertEmailTemplateDataWas([
            'action_url' => $patient->user->emailVerificationURL(),
        ]);
    }

    public function test_appointment_scheduled_event_fills_correctly_patient_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentScheduled($appointment));

        $this->assertEmailWasSentTo($appointment->patient->user->email);
        $this->assertEmailTemplateNameWas('patient.appointment.new');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'harvey_id' => $appointment->patient->user->id,
            'patient_name' => $appointment->patient->user->first_name,
            'patient_phone' => $appointment->patient->user->phone,
            'patient_state' => $appointment->patient->user->state,
            'phone_number' => $appointment->patient->user->phone,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
        ]);
    }

    public function test_appointment_scheduled_event_fills_correctly_practitioner_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentScheduled($appointment));

        $this->assertEmailWasSentTo($appointment->practitioner->user->email);
        $this->assertEmailTemplateNameWas('practitioner.appointment.new');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->full_name,
            'patient_state' => $appointment->patient->user->state,
            'patient_phone' => $appointment->patient->user->phone,
            'practitioner_name' => $appointment->practitioner->user->first_name,
            'practitioner_state' => $appointment->practitioner->user->state,
        ]);
    }

    public function test_appointment_canceled_event_fills_correctly_patient_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentCanceled($appointment));

        $this->assertEmailWasSentTo($appointment->patient->user->email);
        $this->assertEmailTemplateNameWas('patient.appointment.canceled');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->first_name,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);
    }

    public function test_appointment_canceled_event_fills_correctly_practitioner_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentCanceled($appointment));

        $this->assertEmailWasSentTo($appointment->practitioner->user->email);
        $this->assertEmailTemplateNameWas('practitioner.appointment.canceled');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->full_name,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->first_name,
            'practitioner_state' => $appointment->practitioner->user->state,
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);
    }

    public function test_appointment_updated_event_fills_correctly_patient_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentUpdated($appointment));

        $this->assertEmailWasSentTo($appointment->patient->user->email);
        $this->assertEmailTemplateNameWas('patient.appointment.updated');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'harvey_id' => $appointment->patient->user->id,
            'patient_name' => $appointment->patient->user->full_name,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);
    }

    public function test_appointment_updated_event_fills_correctly_practitioner_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentUpdated($appointment));

        $this->assertEmailWasSentTo($appointment->practitioner->user->email);
        $this->assertEmailTemplateNameWas('practitioner.appointment.updated');
        $this->assertEmailTemplateDataWas([
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $appointment->patient->user->full_name,
            'patient_phone' => $appointment->patient->user->phone,
            'patient_state' => $appointment->patient->user->state,
            'practitioner_name' => $appointment->practitioner->user->full_name,
            'practitioner_state' => $appointment->practitioner->user->state,
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);
    }

    public function test_labtest_processing_notification()
    {
      // create Lab Test instance
      $lab_test = factory(LabTest::class)->create();

      $information = factory(LabTestInformation::class)->create(['sku_id'=>$lab_test->sku_id]);

      // change its status to processing
      $lab_test->status_id = LabTest::PROCESSING_STATUS_ID;
      $lab_test->save();

      // test email is being sent
      $this->assertEmailWasSentTo($lab_test->patient->user->email);
      $this->assertEmailTemplateNameWas('patient.lab_test.processing');

      $this->assertEmailTemplateDataWas([
        'lab_test_name' => $lab_test->sku->name,
        'lab_name' =>   $lab_test->information->lab_name,
      ]);
    }

}
