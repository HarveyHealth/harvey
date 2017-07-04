<?php

namespace Tests\Feature;

use App\Models\{Admin, Patient, Practitioner, LabOrder};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use ResponseCode;


class LabOrderTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_allows_a_patient_to_retrieve_only_his_labs_orders()
    {
        $labOrders = factory(LabOrder::class, 3)->create();
        $patient = $labOrders->first()->patient;

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/lab/orders');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(1, $response->original['data']);
    }

    public function test_it_allows_a_practitioner_to_retrieve_only_his_labs_orders()
    {
        $labOrders = factory(LabOrder::class, 3)->create();
        $practitioner = $labOrders->first()->practitioner;

        Passport::actingAs($practitioner->user);
        $response = $this->json('GET', 'api/v1/lab/orders');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(1, $response->original['data']);
    }

    public function test_it_allows_an_admin_to_retrieve_lab_orders()
    {
        factory(LabOrder::class, 3)->create();

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        $response = $this->json('GET', 'api/v1/lab/orders');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(3, $response->original['data']);
    }

    public function test_it_allows_an_admin_to_retrieve_a_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        $response = $this->json('GET', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labOrder->id, $response->original['data']['id']);
    }

    public function test_it_allows_a_practitioner_to_retrieve_his_own_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->practitioner->user);
        $response = $this->json('GET', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labOrder->id, $response->original['data']['id']);
    }

    public function test_it_allows_a_patient_to_retrieve_his_own_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->patient->user);
        $response = $this->json('GET', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labOrder->id, $response->original['data']['id']);
    }

    public function test_it_does_not_allows_a_practitioner_to_retrieve_others_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs(factory(Practitioner::class)->create()->user);
        $response = $this->json('GET', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_patient_to_retrieve_others_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('GET', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_create_a_lab_order()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'practitioner_id' => $practitioner->id,
            'patient_id' => $patient->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/orders', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['patient_id' => "{$patient->id}"]);
        $response->assertJsonFragment(['practitioner_id' => "{$practitioner->id}"]);

        $this->assertDatabaseHas('lab_orders', ['patient_id' => $patient->id]);
        $this->assertDatabaseHas('lab_orders', ['practitioner_id' => $practitioner->id]);
    }

    public function test_patient_id_is_required_when_creating_a_lab_order()
    {
        $practitioner = factory(Practitioner::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'practitioner_id' => $practitioner->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/orders', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }

    public function test_practitioner_id_is_required_when_creating_a_lab_order()
    {
        $patient = factory(Patient::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'patient_id' => $patient->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/orders', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }

    public function test_it_does_not_allows_a_practitioner_to_create_a_lab_order()
    {
        Passport::actingAs(factory(Practitioner::class)->create()->user);

        $response = $this->json('POST', 'api/v1/lab/orders');

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_patient_to_create_a_lab_order()
    {
        Passport::actingAs(factory(Patient::class)->create()->user);

        $response = $this->json('POST', 'api/v1/lab/orders');

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_doesn_not_allows_an_admin_to_update_a_completed_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create(['status' => 'complete']);

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'shipment_code' => 'test1234',
        ];

        $response = $this->json('PATCH', "api/v1/lab/orders/{$labOrder->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_update_a_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'shipment_code' => 'test1234',
        ];

        $response = $this->json('PATCH', "api/v1/lab/orders/{$labOrder->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_orders', $parameters);
    }

    public function test_it_allows_a_practitioner_to_update_his_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->practitioner->user);

        $parameters = [
            'shipment_code' => 'test1234',
        ];

        $response = $this->json('PATCH', "api/v1/lab/orders/{$labOrder->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_orders', $parameters);
    }

    public function test_it_does_not_allows_a_patient_to_update_his_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->patient->user);

        $parameters = [
            'shipment_code' => 'test1234',
        ];

        $response = $this->json('PATCH', "api/v1/lab/orders/{$labOrder->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_patient_to_delete_his_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->patient->user);

        $response = $this->json('DELETE', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_practitioner_to_delete_his_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs($labOrder->practitioner->user);

        $response = $this->json('DELETE', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_delete_a_lab_order()
    {
        $labOrder = factory(LabOrder::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $response = $this->json('DELETE', "api/v1/lab/orders/{$labOrder->id}");

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }

    public function test_a_lab_order_is_set_as_complete_after_lab_tests_are_complete()
    {
        $labOrder = factory(LabOrder::class)->create(['status' => 'pending']);
        $labTest = factory(LabTest::class, 3)->create(['lab_order_id' => $labOrder->id]);

        $this->assertEquals('pending', $labOrder->status);

        $labOrder->labTests->every->markAsComplete();

        $this->assertEquals('complete', $labOrder->fresh()->status);
    }

    public function test_a_lab_order_is_set_as_canceled_after_lab_tests_are_canceled()
    {
        $labOrder = factory(LabOrder::class)->create(['status' => 'pending']);
        $labTest = factory(LabTest::class, 3)->create(['lab_order_id' => $labOrder->id]);

        $this->assertEquals('pending', $labOrder->status);

        $labOrder->labTests->every->markAsCanceled();

        $this->assertEquals('canceled', $labOrder->fresh()->status);
    }
}
