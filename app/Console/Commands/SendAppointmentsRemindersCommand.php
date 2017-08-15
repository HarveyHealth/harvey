<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Appointment, AppointmentReminder};
use Carbon\Carbon;

class SendAppointmentsRemindersCommand extends Command
{
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
     public function handle()
     {
        $this->info('Step 1/2: Looking for pending Appointments in the next 24hs...');
        $appointments = Appointment::pendingInTheNext24hs()->get();

        if (empty($appointments)) {
            $this->info('No Appointments found.');
        } else {
            $this->info("[Found {$appointments->count()} Appointments.]");
            $this->info('Processing Appointments...');
            $this->info('');

            $totalClientEmailSent = $appointments->map->sendClientReminderEmail24Hs()->filter()->count();
            $totalDoctorEmailSent = $appointments->map->sendDoctorReminderEmail24Hs()->filter()->count();
            $totalClientSmsSent = $appointments->map->sendClientReminderSms24Hs()->filter()->count();
            $totalDoctorSmsSent = $appointments->map->sendDoctorReminderSms24Hs()->filter()->count();
        }

        $this->info("Step 2/2 Looking for pending Appointments in the next 12hs belonging to Patients who didn't completed the Intake form...");
        $appointments = Appointment::pendingInTheNext12hs()->emptyPatientIntake()->get();

        if (empty($appointments)) {
            $this->info('No Appointments found.');
        } else {
            $this->info("[Found {$appointments->count()} Appointments.]");
            $this->info('Processing Appointments...');
            $this->info('');

            $totalIntakeSmsSent = $appointments->map->sendClientIntakeReminderSms12Hs()->filter()->count();
        }

        $this->info("Done.");
        $this->info('');
        $this->info("[{$totalClientEmailSent} Client Email appointments 24hs reminders sent.]");
        $this->info("[{$totalDoctorEmailSent} Doctor Email appointments 24hs reminders sent.]");
        $this->info("[{$totalClientSmsSent} Client SMS Appointments 24hs reminders sent.]");
        $this->info("[{$totalDoctorSmsSent} Doctor SMS Appointments 24hs reminders sent.]");
        $this->info("[{$totalIntakeSmsSent} Intake SMS Appointments 12hs reminders sent.]");
    }
}
