<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogTailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:tail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tails the local Laravel log for debugging purposes';

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
         $logFile = $this->getSingleLogFile();

         passthru("tail -f $logFile");
     }

    protected function logDirectory()
    {
        return storage_path() . '/logs';
    }

    protected function getSingleLogFile()
    {
        return $this->logDirectory() . '/laravel.log';
    }
}
