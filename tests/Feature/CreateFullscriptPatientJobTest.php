<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\CreateFullscriptPatient;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{User, Patient};
use App\Events\UserRegistered;
use App\Lib\Clients\Fullscript;
use Mockery;


class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /*
    public function testJobDispatched()
    {
        Bus::fake();
        $user = factory(User::class)->create();
        event(new UserRegistered($user));
        Bus::assertDispatched(CreateFullscriptPatient::class, function ($job) use ($user) {
            return $job->user->id === $user->id;
        });
    }
    */

    public function testCreateUser()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $fullscript = Mockery::mock(Fullscript::class);

        $fullscript->shouldReceive('getPatients')
            ->with($user->id)
            ->andReturn([]);

        $fullscript->shouldReceive('getPatients')
            ->with("",$user->email)
            ->andReturn([]);

        $fullscript->shouldNotReceive('updatePatient');

        $fullscript->shouldReceive('createPatient')
            ->with([
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "email" => $user->email,
                "date_of_birth"=> date('Y-m-d',strtotime($patient->birthdate)),
                "external_ref" => $user->id,
            ]);
        // register mock
        app()->instance(Fullscript::class, $fullscript);


        $job = new CreateFullscriptPatient($user);
        $job->handle();
    }

    public function testUpdateUser()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $fullscript = Mockery::mock(Fullscript::class);

        $fullscript->shouldReceive('getPatients')
            ->with($user->id)
            ->andReturn([]);

        $fullscript->shouldReceive('getPatients')
            ->with("",$user->email)
            ->andReturn([
                [
                    "id" => "d290f1ee-6c54-4b01-90e6-d701748f0851",
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "date_of_birth" => date('Y-m-d',strtotime($patient->birthdate)),
                ]
            ]);

        $fullscript->shouldReceive('updatePatient')
            ->with("d290f1ee-6c54-4b01-90e6-d701748f0851", [
                "external_ref" => $user->id,
            ]);


        $fullscript->shouldNotReceive('createPatient');

        // register mock
        app()->instance(Fullscript::class, $fullscript);


        $job = new CreateFullscriptPatient($user);
        $job->handle();
    }


    public function testFoundUser()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $fullscript = Mockery::mock(Fullscript::class);

        $fullscript->shouldReceive('getPatients')
            ->with($user->id)
            ->andReturn([
                [
                    "id" => "d290f1ee-6c54-4b01-90e6-d701748f0851",
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "date_of_birth" => date('Y-m-d',strtotime($patient->birthdate)),
                    "external_ref" => $user->id,
                ]
            ]);

        $fullscript->shouldNotReceive('updatePatient');
        $fullscript->shouldNotReceive('createPatient');

        // register mock
        app()->instance(Fullscript::class, $fullscript);


        $job = new CreateFullscriptPatient($user);
        $job->handle();
    }

}
