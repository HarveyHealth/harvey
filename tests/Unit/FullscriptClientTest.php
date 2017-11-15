<?php

namespace Tests\Unit;

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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;


class FullscriptClientTest extends TestCase
{
    use DatabaseMigrations;




    public function testListPatients()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $client = Mockery::mock(Client::class);

        $endpoint = trim(config('services.fullscript.api_host'), '/') . '/';


        $fullscript = new Fullscript($client);

        $client->shouldReceive('get')
            ->with($endpoint.'patients', [
                'query' => [
                    'external_ref' => $user->id,
                ],
                'headers' => [
                    'X-API-Key' => config('services.fullscript.api_key'),
                    'X-FS-Clinic-Key' => config('services.fullscript.clinic_key'),
                    'Content-Type' => 'application/json'
                ]
            ])
            ->andReturn(new Response(
                $status = 200,
                $headers = [],
                $body = json_encode([])
            ));

        $fullscript->getPatients($user->id);

        $client->shouldReceive('get')
            ->with($endpoint.'patients', [
                'query' => [
                    'email' => $user->email,
                ],
                'headers' => [
                    'X-API-Key' => config('services.fullscript.api_key'),
                    'X-FS-Clinic-Key' => config('services.fullscript.clinic_key'),
                    'Content-Type' => 'application/json'
                ]
            ])
            ->andReturn(new Response(
                $status = 200,
                $headers = [],
                $body = json_encode([])
            ));

        $fullscript->getPatients('',$user->email);
    }
    public function testCreatePatient()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $client = Mockery::mock(Client::class);

        $endpoint = trim(config('services.fullscript.api_host'), '/') . '/';


        $fullscript = new Fullscript($client);

        $client->shouldReceive('post')
            ->with($endpoint.'patients', [

                'body' => json_encode([
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "date_of_birth"=> date('Y-m-d',strtotime($patient->birthdate)),
                    "external_ref" => $user->id,
                ],JSON_FORCE_OBJECT),

                'headers' => [
                    'X-API-Key' => config('services.fullscript.api_key'),
                    'X-FS-Clinic-Key' => config('services.fullscript.clinic_key'),
                    'Content-Type' => 'application/json'
                ]
            ])
            ->andReturn(new Response(
                $status = 200,
                $headers = [],
                $body = json_encode([])
            ));


        $fullscript->createPatient([
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "date_of_birth"=> date('Y-m-d',strtotime($patient->birthdate)),
            "external_ref" => $user->id,
        ]);
    }

    public function testUpdatePatient()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        // Mock API client to avoid making API calls
        $client = Mockery::mock(Client::class);

        $endpoint = trim(config('services.fullscript.api_host'), '/') . '/';


        $fullscript = new Fullscript($client);

        $client->shouldReceive('put')
            ->with($endpoint.'patients/'.'ANY_ID_VALUE', [
                'body' => json_encode([
                    "external_ref" => $user->id,
                ],JSON_FORCE_OBJECT),
                'headers' => [
                    'X-API-Key' => config('services.fullscript.api_key'),
                    'X-FS-Clinic-Key' => config('services.fullscript.clinic_key'),
                    'Content-Type' => 'application/json'
                ]
            ])
            ->andReturn(new Response(
                $status = 200,
                $headers = [],
                $body = json_encode([])
            ));


        $fullscript->updatePatient('ANY_ID_VALUE',[
            "external_ref" => $user->id,
        ]);
    }
}
