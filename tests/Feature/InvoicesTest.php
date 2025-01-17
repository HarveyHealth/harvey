<?php

namespace Tests\Feature;

use App\Models\{
    DiscountCode,
    Patient,
    Invoice,
    InvoiceItem,
    Admin,
    User
};

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\LabOrder;
use App\Models\Appointment;
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
        $this->assertEquals(count($response->original->data), 5);

        // And each invoice belongs to the patient
        foreach ($response->original->data as $item) {
            $this->assertEquals($item->attributes->patient_id, $patient->id);
        }
    }

    public function test_it_does_not_allows_a_patient_to_view_others_invoices()
    {
        $patient = factory(Patient::class)->create();
        $patient->invoices()->save(factory(Invoice::class)->make());

        $patient1 = factory(Patient::class)->create();
        $patient1->invoices()->save(factory(Invoice::class)->make());

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/invoices/{$patient->invoices()->first()->id}");
        $response->assertStatus(ResponseCode::HTTP_OK);

        Passport::actingAs($patient1->user);
        $response = $this->json('GET', "api/v1/invoices/{$patient->invoices()->first()->id}");
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_view_all_invoices()
    {
        factory(Patient::class, 2)->create()->each(function ($patient) {
            $patient->invoices()->saveMany(factory(Invoice::class, 3)->make());
        });

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('GET', 'api/v1/invoices');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(6, $response->original->data);
    }

    public function test_it_includes_appointment()
    {
        // creates a invoice for an appointment
        $invoice = factory(Invoice::class)->create();

        $appointment = factory(Appointment::class)->create([
            'invoice_id' => $invoice->id,
        ]);

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);

        $response = $this->json('GET', "api/v1/invoices/{$invoice->id}?include=appointment");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($appointment->id, $response->original->data->attributes->appointment_id);

        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
                'links',
                'relationships'
            ],
            'included' => [
                '*' => [
                    'type',
                    'id',
                    'attributes',
                    'links',
                ],
            ]
        ]);

        $this->assertEquals($appointment->id, $response->original->included[0]->id);
    }

    public function test_it_includes_lab_order()
    {
        // creates a invoice for an appointment
        $invoice = factory(Invoice::class)->create();

        $lab_order = factory(LabOrder::class)->create([
            'invoice_id' => $invoice->id,
        ]);

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);

        $response = $this->json('GET', "api/v1/invoices/{$invoice->id}?include=lab_order");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($lab_order->id, $response->original->data->attributes->lab_order_id);

        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
                'links',
                'relationships'
            ],
            'included' => [
                '*' => [
                    'type',
                    'id',
                    'attributes',
                    'links',
                ],
            ]
        ]);

        $this->assertEquals($lab_order->id, $response->original->included[0]->id);
    }

    public function test_it_can_include_items()
    {
        // Given a practitioner with a scheduled invoice
        $invoice = factory(Invoice::class)->create();

        // creates some invoice items
        factory(InvoiceItem::class)->create([
            'invoice_id' => $invoice->id,
        ]);

        $admin = factory(Admin::class)->create();
        // When they attempt to view the information for a specific invoice
        // and include patient and user info
        Passport::actingAs($admin->user);
        $response = $this->json('GET', "api/v1/invoices/{$invoice->id}?include=items");

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And they can see the invoice information
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
                'links',
                'relationships'
            ],
            'included' => [
                '*' => [
                    'type',
                    'id',
                    'attributes',
                    'links',
                ],
            ]
        ]);
    }

}
