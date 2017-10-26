<?php

namespace Tests\Feature;

use App\Console\Commands\GetAPICalendarCredentialsCommand;
use Tests\TestCase;

class GetAPICalendarCredentialsCommandTest extends TestCase
{
    protected function getAPICalendarCredentialsCommandOutput()
    {
        return $this->getCommandOutput('calendar:get_credentials');
    }

    public function test_it_returns_an_error_if_s3_credentials_are_wrong()
    {
        config(['filesystems.disks.s3.secret' => 'wrong_key']);

        $output = $this->getAPICalendarCredentialsCommandOutput();
        $this->assertContains('Unable to download passport keys.', $output[0]);
    }

    public function test_it_retrieves_google_api_calendar_keys()
    {
        @unlink('storage/calendar_api/access_token.json');
        @unlink('storage/calendar_api/client_secret.json');
        @rmdir('storage/calendar_api');

        $output = $this->getAPICalendarCredentialsCommandOutput();

        $this->assertFileExists('storage/calendar_api/access_token.json');
        $this->assertFileExists('storage/calendar_api/client_secret.json');
        $this->assertEquals('Harvey Google Calendar API credentials downloaded successfully!', $output[0]);
    }
}
