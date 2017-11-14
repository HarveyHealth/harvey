<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\CreateFullscriptPatient;
use Illuminate\Support\Facades\Redis;

class ImportUsersToFullscript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fullscript:sync_users';
    const LAST_PROCESSED_ID_REDIS_KEY = 'fullscript:sync_users:last_processed_id';

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
        $this->info('Syncing');

        $query = User::has('patient');
        $count = $query->count();

        $bar = $this->output->createProgressBar($count);

        $lastProcessedId = Redis::get(self::LAST_PROCESSED_ID_REDIS_KEY);
        if (is_numeric($lastProcessedId)) {
            $bar->setProgress($lastProcessedId);
            $query->where('id','>',$lastProcessedId);
        }

        foreach ($query->cursor() as $user){
            dispatch(new CreateFullscriptPatient($user));
            $bar->advance();
        }
        $bar->finish();
    }
}
