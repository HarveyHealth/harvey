<?php

namespace Tests;

use App\Jobs\SendTransactionalEmail;
use App\Lib\SMS;
use App\Observers\{AppointmentObserver, UserObserver};
use Illuminate\Contracts\Console\Kernel;
use Mockery;

trait CreatesApplication
{
    protected $email_mock;
    protected $sms_mock;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app['env'] = 'testing';

        //Mock AppointmentObserver class to avoid creating Google Calendar events when testing.
        $appointment_observer_mock = Mockery::mock(AppointmentObserver::class)->makePartial();
        $appointment_observer_mock->shouldReceive('creating')->andReturn(true);
        app()->instance(AppointmentObserver::class, $appointment_observer_mock);

        //Mock UserObserver class to avoid update of Fullscript users when testing.
        $user_observer_mock = Mockery::mock(UserObserver::class)->makePartial();
        $user_observer_mock->shouldReceive('updated')->andReturn(true);
        app()->instance(UserObserver::class, $user_observer_mock);

        //Mock SMS class to avoid log entries and to check if texts were sent.
        $this->sms_mock = Mockery::mock(SMS::class)->makePartial();
        $this->sms_mock->shouldReceive('sendMessageToNumber')->withAnyArgs()->andReturn(true);
        app()->instance(SMS::class, $this->sms_mock);

        //Mock SendTransactionalEmail class to be able to check if mails were sent.
        $this->email_mock = Mockery::mock(SendTransactionalEmail::class)->makePartial();
        app()->instance(SendTransactionalEmail::class, $this->email_mock);

        return $app;
    }
}
