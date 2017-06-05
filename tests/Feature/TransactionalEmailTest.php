<?php

namespace Tests\Listeners;

use App\Events\AppointmentCanceled;
use App\Events\AppointmentScheduled;
use App\Events\AppointmentUpdated;
use App\Events\UserRegistered;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserListenersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_registered_event_fills_correctly_welcome_email()
    {
        $patient = factory(Patient::class)->create();

        event(new UserRegistered($patient->user));

        $this->assertEmailWasSentTo($patient->user->email);
        $this->assertEmailTemplateNameWas('patient.welcome');
        $this->assertEmailTemplateDataWas([
            'name' => $patient->user->fullName(),
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
            'practitioner_name' => $appointment->practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'harvey_id' => $appointment->patient->user->id,
            'phone_number' => $appointment->patient->user->phone,
            'patient_name' => $appointment->patient->user->first_name,
            'patient_phone' => $appointment->patient->user->phone
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
            'patient_name' => $appointment->patient->user->fullName(),
            'patient_phone' => $appointment->patient->user->phone,
            'practitioner_name' => $appointment->practitioner->user->first_name,
        ]);
    }

    public function test_appointment_canceled_event_fills_correctly_patient_email()
    {
        $appointment = factory(Appointment::class)->create();

        event(new AppointmentCanceled($appointment));

        $this->assertEmailWasSentTo($appointment->patient->user->email);
        $this->assertEmailTemplateNameWas('patient.appointment.canceled');
        $this->assertEmailTemplateDataWas([
            'practitioner_name' => $appointment->practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
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
            'patient_name' => $appointment->patient->user->fullName(),
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
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
            'practitioner_name' => $appointment->practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
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
            'patient_name' => $appointment->patient->user->fullName(),
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);
    }
}
