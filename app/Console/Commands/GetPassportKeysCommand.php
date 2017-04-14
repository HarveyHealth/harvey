<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetPassportKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:getkeys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads Harvey Passport Keys from S3.';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $private_key = Storage::disk('s3')->get('oauth-private.key');
            file_put_contents('storage/oauth-private.key', $private_key);
    
            $public_key = Storage::disk('s3')->get('oauth-public.key');
            file_put_contents('storage/oauth-public.key', $public_key);
            
            $this->info('Passport keys downloaded successfully!');
        } catch (\Exception $e) {
            $this->error("Unable to download passport keys. {$e->getMessage()}");
            throw new \Exception($e);
        }
    }
}
