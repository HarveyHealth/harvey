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
        $this->info('Step 1/3: Looking for pending Appointments in the next 24hs...');
        $appointments = Appointment::pendingInTheNext24hs()->get();

        $this->info("[Found {$appointments->count()} Appointments.]");
        $this->info('Processing Appointments...');
        $this->info('');

        $totalClientEmail24hsSent = $appointments->map->sendClientReminderEmail24Hs()->filter()->count();
        $totalDoctorEmail24hsSent = $appointments->map->sendDoctorReminderEmail24Hs()->filter()->count();
        $totalClientSms24hsSent = $appointments->map->sendClientReminderSms24Hs()->filter()->count();
        $totalDoctorSms24hsSent = $appointments->map->sendDoctorReminderSms24Hs()->filter()->count();

        $this->info("Step 2/3: Looking for pending Appointments in the next 12hs belonging to Patients who didn't completed the Intake form...");
        $appointments = Appointment::pendingInTheNext12hs()->emptyPatientIntake()->get();

        $this->info("[Found {$appointments->count()} Appointments.]");
        $this->info('Processing Appointments...');
        $this->info('');

        $totalIntakeSmsSent = $appointments->map->sendClientIntakeReminderSms12Hs()->filter()->count();

        $this->info("Step 3/3: Looking for pending Appointments in the next hour...");
        $appointments = Appointment::pendingInTheNextHour()->get();

        $this->info("[Found {$appointments->count()} Appointments.]");
        $this->info('Processing Appointments...');
        $this->info('');

        $totalClientSms1hsSent = $appointments->map->sendClientReminderSms1Hs()->filter()->count();
        $totalDoctorSms1hsSent = $appointments->map->sendDoctorReminderSms1Hs()->filter()->count();

        $this->info("Done.");
        $this->info('');
        $this->info("[{$totalClientEmail24hsSent} Client Email appointments 24hs reminders sent.]");
        $this->info("[{$totalDoctorEmail24hsSent} Doctor Email appointments 24hs reminders sent.]");
        $this->info("[{$totalClientSms24hsSent} Client SMS Appointments 24hs reminders sent.]");
        $this->info("[{$totalDoctorSms24hsSent} Doctor SMS Appointments 24hs reminders sent.]");
        $this->info("[{$totalIntakeSmsSent} Intake SMS Appointments 12hs reminders sent.]");
        $this->info("[{$totalClientSms1hsSent} Client SMS Appointments 1hs reminders sent.]");
        $this->info("[{$totalDoctorSms1hsSent} Doctor SMS Appointments 1hs reminders sent.]");
    }
}
