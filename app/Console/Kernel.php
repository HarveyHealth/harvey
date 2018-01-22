<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AdminCreateCommand::class,
        Commands\BillingReport::class,
        Commands\GetAPICalendarCredentialsCommand::class,
        Commands\GetPassportKeysCommand::class,
        Commands\ImportDiscountCodesCommand::class,
        Commands\ImportUsersToFullscript::class,
        Commands\LogTailCommand::class,
        Commands\MakeComponentCommand::class,
        Commands\MakeRepositoryCommand::class,
        Commands\MakeViewCommand::class,
        Commands\PractitionerCreateCommand::class,
        Commands\ReportsMonthlyCommand::class,
        Commands\SendAppointmentsRemindersCommand::class,
        Commands\SetNginxConfigCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('appointments:reminders')->hourly()->withoutOverlapping();
        $schedule->command('messages:send-unread-messages-notifications')->cron('*/15 * * * * *')->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
