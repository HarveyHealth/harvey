<?php
namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Jobs\CreateFullscriptPatient;
use App\Lib\Clients\Fullscript;
use App\Models\{Patient, User};
use Illuminate\Foundation\Testing\{DatabaseMigrations, DatabaseTransactions, WithoutMiddleware};
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Mockery;

class UpdateFullscriptPatientJobTest extends TestCase
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

    public function testUpdateUser()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        $previous_email = $user->email;

        $user->email = "mynewemail@user.com";

        // Mock API client to avoid making API calls
        $fullscript = Mockery::mock(Fullscript::class);

        $fullscript->shouldReceive('getPatients')
            ->with($user->id)
            ->andReturn([
                [
                    "id" => "d290f1ee-6c54-4b01-90e6-d701748f0851",
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $previous_email,
                    "date_of_birth" => date('Y-m-d', strtotime($patient->birthdate)),
                ]
            ]);


        $fullscript->shouldReceive('updatePatient')
            ->with("d290f1ee-6c54-4b01-90e6-d701748f0851", [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "email" => $user->email,
                "date_of_birth"=> date('Y-m-d', strtotime($patient->birthdate))
            ]);
        // register mock
        app()->instance(Fullscript::class, $fullscript);


        $user->save();
    }
}
