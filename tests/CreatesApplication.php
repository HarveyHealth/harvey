<?php

namespace Tests;

use App\Jobs\SendTransactionalEmail;
use Illuminate\Contracts\Console\Kernel;
use Twilio\Rest\Client as Twilio;
use Mockery;

trait CreatesApplication
{
    protected $emailMock;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app['env'] = 'testing';

        app()->instance(Twilio::class, Mockery::mock(Twilio::class));

        $this->mockSendTransactionalEmail();

        return $app;
    }

    public function mockSendTransactionalEmail()
    {
        $this->emailMock = Mockery::mock(SendTransactionalEmail::class)->makePartial();

        app()->instance(SendTransactionalEmail::class, $this->emailMock);
    }

}
