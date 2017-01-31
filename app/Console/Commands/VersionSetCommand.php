<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VersionSetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the version ID';

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
        // we don't need to do this on local
        if (app()->environment('local')) {
            return true;
        }

        $text = 'Harvey';

        $path = base_path('version.txt');
        $handle = fopen($path, 'w');
        fputs($handle, $text);
        fclose($handle);

        return true;
    }
}
