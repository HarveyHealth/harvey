<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetNginxConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nginx:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Nginx configuration file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->inDevEnvironment() && $this->hasDevConfig()) {
            $this->files->move(base_path('nginx.conf'), base_path('nginx.conf.backup'));
            $this->files->move(base_path('nginx.conf.dev'), base_path('nginx.conf'));
        }
    
        $this->info('Nginx configuration file set for ' . \App::environment() . ' environment.');
    }
    
    public function hasDevConfig()
    {
        return $this->files->exists(base_path('nginx.conf.dev'));
    }
    
    public function inDevEnvironment()
    {
        return \App::environment('dev', 'staging');
    }
}
