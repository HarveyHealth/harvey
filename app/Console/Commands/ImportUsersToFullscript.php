<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\CreateFullscriptPatient;

class ImportUsersToFullscript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fullscript:import_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizes clients database with fullscript';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Importing');

        $bar = $this->output->createProgressBar(User::has('patient')->count());
        foreach (User::has('patient')->cursor() as $user){
            dispatch(new CreateFullscriptPatient($user));
            $bar->advance();
        }
        $bar->finish();
    }
}
