<?php

namespace Tests\Feature;

use App\Models\{
    DiscountCode,
    Patient,
    Invoice,
    InvoiceItem,
    User
};

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon, Log, ResponseCode;

class InvoiceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_allows_a_patient_to_view_their_own_invoices()
    {
        // Given a patient with 5 invoices
        $patient = factory(Patient::class)->create();
        $patient->invoices()->saveMany(factory(Invoice::class, 5)->make());

        // And there are other invoices in the database
        factory(Invoice::class, 10)->create();

        // When the patient requests to list out the invoices
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/invoices');

        // Then we should only see 5 invoices
        $this->assertEquals(count($response->original['data']), 5);

        // And each invoice belongs to the patient
        foreach ($response->original['data'] as $item) {
            $this->assertEquals($item['attributes']['patient_id'], $patient->id);
        }
    }

}
