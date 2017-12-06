<?php

namespace Tests\Feature;

use App\Models\{SoapNote, Attachment, Prescription, LabTestResult, Patient, LabTest};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

use Carbon, Log, ResponseCode;

class SearchTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_finds_soap_note()
    {
        // Given a patient with 5 Soap Notes
        $patient = factory(Patient::class)->create();
        $patient->soapNotes()->saveMany(factory(SoapNote::class, 5)->make());

        // And there are other soap notes in the database
        factory(SoapNote::class, 10)->create();

        // When the patient requests to list out the soap notes
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/search', ['term'=>$patient->user->full_name]);

        //Log::info(print_r($response->original,true));

        // Then we should only see 5 elements
        $this->assertEquals(count($response->original['data']), 5);

        // And each element belongs to the patient
        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }

    public function test_it_finds_attachment()
    {
        $patient = factory(Patient::class)->create();
        $patient->attachments()->saveMany(factory(Attachment::class, 5)->make());

        factory(Attachment::class, 10)->create();

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/search', ['term'=>$patient->user->full_name]);

        $this->assertEquals(count($response->original['data']), 5);

        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }

    public function test_it_finds_prescription()
    {
        $patient = factory(Patient::class)->create();
        $patient->prescriptions()->saveMany(factory(Prescription::class, 5)->make());

        factory(Prescription::class, 10)->create();

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/search', ['term'=>$patient->user->full_name]);

        $this->assertEquals(count($response->original['data']), 5);

        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }

    public function test_it_finds_labresult()
    {

        $lab_test = factory(LabTest::class)->create();

        $patient = $lab_test->labOrder->patient;
        factory(LabTestResult::class, 5)->create(['lab_test_id' => $lab_test->id]);

        factory(LabTestResult::class, 10)->create();

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/search', ['term'=>$patient->user->full_name]);

        Log::info($response->original);

        $this->assertEquals(5, count($response->original['data']));

        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['lab_test_id'], $lab_test->id);
        }
    }
}
