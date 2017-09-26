<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UtilityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'utility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Does stuff';

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
        $csv = new \App\Lib\CSV('/Users/agt/Downloads/null.csv');

        foreach ($csv as $line){
            print_r($line);
            list($id, $first_name, $last_name, $email, $zip) = $line;
            $user = \App\Models\User::findOrFail($id);
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->zip = $zip;
             }
    }
}
