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
        Commands\LogTailCommand::class,
        Commands\MakeViewCommand::class,
        Commands\MakeComponentCommand::class,
        Commands\MakeRepositoryCommand::class,
        Commands\SetNginxConfigCommand::class,
        Commands\GetPassportKeysCommand::class,
        Commands\PractitionerCreateCommand::class,
        Commands\AdminCreateCommand::class,
        Commands\SendAppointmentsRemindersCommand::class,
        Commands\SendUnreadMessageEmailNotificationsCommand::class,
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
        $schedule->command('messages:email')->cron('*/15 * * * * *')->withoutOverlapping();
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
