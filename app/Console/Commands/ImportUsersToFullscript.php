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

        // resume progress if last processed id is available
        $lastProcessedId = Redis::get(self::LAST_PROCESSED_ID_REDIS_KEY);
        if (is_numeric($lastProcessedId)) {
            if ($lastProcessedId < $query->max('id')){
                $this->info('Resuming progress from id: ' . $lastProcessedId);
                $bar->setProgress(User::has('patient')->where('id','<',$lastProcessedId)->count());
                $query->where('id','>=',$lastProcessedId);
            }
        }

        // proccess one by one
        foreach ($query->orderBy('id','asc')->cursor() as $user){
            try{
                dispatch(new CreateFullscriptPatient($user));
            }
            catch(\Exception $e){
                $bar->clear();
                $this->info('Saving progress');
                Redis::set(self::LAST_PROCESSED_ID_REDIS_KEY,$user->id);
                throw $e;
            }
            $bar->advance();
        }
        Redis::set(self::LAST_PROCESSED_ID_REDIS_KEY,$user->id);
        $bar->finish();
    }
}
