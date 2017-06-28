<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendTransactionalEmail;
use App\Models\{Appointment, AppointmentReminder};
use Carbon\Carbon;

class SendAppointmentsRemindersCommand extends Command
{
    protected $sendTransactionalEmail;
    protected $totalSent;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Appointments reminders to users.';

    public function __construct(SendTransactionalEmail $sendTransactionalEmail)
    {
        parent::__construct();
        $this->sendTransactionalEmail = $sendTransactionalEmail;
        $this->totalSent = 0;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
     public function handle()
     {
        $this->info('Looking for pending Appointments in the next 24hs...');
        $appointments = Appointment::pendingInTheNext24hs()->get();

        if (empty($appointments)) {
            return true;
        } else {
            $this->info("Found {$appointments->count()} Appointment" . (1 == $appointments->count() ? '.' : 's.'));
        }

        $this->info('Processing Appointments...');

        foreach ($appointments as $appointment) {
            $this->sendPatientReminderEmail($appointment);
        }

        $this->info("Done. [{$this->totalSent} Appointment" . (1 == $this->totalSent ? '' : 's') . ' reminder sent.]');
    }

    protected function sendPatientReminderEmail(Appointment $appointment)
    {
        $recipient = $appointment->patient->user;

        if ($appointment->wasPatientReminderEmailSent()) {
            $this->info("User #{$recipient->id} was already email notified about Appointment #{$appointment->id}. Skipping.");
        } else {
            $this->info("Sending {$recipient->type} reminder to User #{$recipient->id} about Appointment #{$appointment->id}.");

            $this->sendTransactionalEmail
                ->setTo($recipient->email)
                ->setTemplate('patient.appointment.reminder')
                ->setTemplateModel([
                    'practitioner_name' => $appointment->practitioner->user->fullName(),
                    'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
                    'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
                    'harvey_id' => $recipient->id,
                    'patient_name' => $recipient->first_name,
                    'patient_phone' => $recipient->phone,
            ]);

            dispatch($this->sendTransactionalEmail);

            $appointment->setPatientReminderEmailSent();
            $this->totalSent ++;
        }
    }
}
