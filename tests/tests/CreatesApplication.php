<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use App\Jobs\SendTransactionalEmail;
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

        $this->mockSendTransactionalEmail();

        return $app;
    }

    public function mockSendTransactionalEmail()
    {
        $this->emailMock = Mockery::mock(SendTransactionalEmail::class)->makePartial();

        app()->instance(SendTransactionalEmail::class, $this->emailMock);
    }

}
