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
        $this->info('Looking for pending Appointments in the next 24hs...');
        $appointments = Appointment::pendingInTheNext24hs()->get();

        if (empty($appointments)) {
            $this->info('No Appointments found.');
        } else {
            $this->info("Found {$appointments->count()} Appointments.");
            $this->info('Processing Appointments...');

            $totalSent = $appointments->map->sendPatientReminderEmail()->filter()->count();
        }

        $this->info("Done. [$totalSent Appointments reminders sent.]");
    }
}
