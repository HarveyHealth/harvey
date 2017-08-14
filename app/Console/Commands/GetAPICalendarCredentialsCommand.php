<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception, File;

class GetAPICalendarCredentialsCommand extends Command
{
    protected $signature = 'calendar:get_credentials';

    protected $description = 'Downloads Harvey Google Calendar API credentials from S3.';

    public function handle()
    {
        try {
            if (!File::isDirectory($path = storage_path() . '/calendar_api/')) {
                File::makeDirectory($path);
            }

            file_put_contents('storage/calendar_api/access_token.json', Storage::disk('s3')->get('calendar/access_token.json'));
            file_put_contents('storage/calendar_api/client_secret.json', Storage::disk('s3')->get('calendar/client_secret.json'));

            $this->info('Harvey Google Calendar API credentials successfully!');
        } catch (Exception $e) {
            $this->error("Unable to download passport keys. {$e->getMessage()}");
            throw new Exception($e);
        }
    }
}
