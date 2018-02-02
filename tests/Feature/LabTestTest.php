<?php

namespace Tests\Feature;

use App\Models\{Admin, Patient, Practitioner, LabTest, LabOrder, SKU};
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon;
use ResponseCode;

class LabTestTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_allows_a_patient_to_retrieve_only_his_labs_tests()
    {
        $labTests = factory(LabTest::class, 3)->create();
        $patient = $labTests->first()->patient;

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/lab/tests');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(1, $response->original->data);
    }

    public function test_it_allows_a_practitioner_to_retrieve_only_his_labs_tests()
    {
        $labTests = factory(LabTest::class, 3)->create();
        $practitioner = $labTests->first()->practitioner;

        Passport::actingAs($practitioner->user);
        $response = $this->json('GET', 'api/v1/lab/tests');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(1, $response->original->data);
    }

    public function test_it_allows_an_admin_to_retrieve_lab_tests()
    {
        factory(LabTest::class, 3)->create();

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        $response = $this->json('GET', 'api/v1/lab/tests');

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertCount(3, $response->original->data);
    }

    public function test_it_allows_an_admin_to_retrieve_a_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        $response = $this->json('GET', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labTest->id, $response->original->data->id);
    }

    public function test_it_allows_a_practitioner_to_retrieve_his_own_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->practitioner->user);
        $response = $this->json('GET', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labTest->id, $response->original->data->id);
    }

    public function test_it_allows_a_patient_to_retrieve_his_own_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->patient->user);
        $response = $this->json('GET', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertEquals($labTest->id, $response->original->data->id);
    }

    public function test_it_does_not_allows_a_practitioner_to_retrieve_others_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs(factory(Practitioner::class)->create()->user);
        $response = $this->json('GET', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_patient_to_retrieve_others_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('GET', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_create_a_lab_test()
    {
        $labOrder = factory(LabOrder::class)->create();
        $sku = factory(SKU::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'lab_order_id' => $labOrder->id,
            'sku_id' => $sku->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/tests', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['lab_order_id' => "{$labOrder->id}"]);
        $response->assertJsonFragment(['sku_id' => "{$sku->id}"]);

        $this->assertDatabaseHas('lab_tests', ['lab_order_id' => $labOrder->id]);
        $this->assertDatabaseHas('lab_tests', ['sku_id' => $sku->id]);
    }

    public function test_it_does_not_allows_a_practitioner_to_create_a_lab_test_if_lab_order_is_assigned_to_another_practitioner()
    {
        $labOrder = factory(LabOrder::class)->create();
        $sku = factory(SKU::class)->create();

        Passport::actingAs(factory(Practitioner::class)->create()->user);

        $parameters = [
            'lab_order_id' => $labOrder->id,
            'sku_id' => $sku->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/tests', $parameters);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_a_practitioner_to_create_a_lab_test_if_lab_order_belongs_to_practitioner()
    {
        $labOrder = factory(LabOrder::class)->create();
        $sku = factory(SKU::class)->create();

        Passport::actingAs($labOrder->practitioner->user);

        $parameters = [
            'lab_order_id' => $labOrder->id,
            'sku_id' => $sku->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/tests', $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment(['lab_order_id' => "{$labOrder->id}"]);
        $response->assertJsonFragment(['sku_id' => "{$sku->id}"]);

        $this->assertDatabaseHas('lab_tests', ['lab_order_id' => $labOrder->id]);
        $this->assertDatabaseHas('lab_tests', ['sku_id' => $sku->id]);
    }

    public function test_sku_id_is_required_when_creating_a_lab_test()
    {
        $labOrder = factory(LabOrder::class)->create();
        $sku = factory(SKU::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'lab_order_id' => $labOrder->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/tests', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }

    public function test_lab_order_id_is_required_when_creating_a_lab_test()
    {
        $labOrder = factory(LabOrder::class)->create();
        $sku = factory(SKU::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'sku_id' => $sku->id,
        ];

        $response = $this->json('POST', 'api/v1/lab/tests', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }

    public function test_it_allows_an_admin_to_update_lab_tests_if_lab_order_is_complete()
    {
        $labTest = factory(LabTest::class)->create(['status' => 'complete']);

        $labTest->LabOrder->markAsComplete();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'status' => 'recommended',
        ];

        $response = $this->json('PATCH', "api/v1/lab/tests/{$labTest->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_tests', ['id' => $labTest->id, 'status_id' => LabTest::RECOMMENDED_STATUS_ID]);
    }

    public function test_it_allows_an_admin_to_update_a_lab_test()
    {
        $labTest = factory(LabTest::class)->create(['status' => 'pending']);

        Passport::actingAs(factory(Admin::class)->create()->user);

        $parameters = [
            'status' => 'canceled',
        ];

        $response = $this->json('PATCH', "api/v1/lab/tests/{$labTest->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_tests', ['status_id' => LabTest::CANCELED_STATUS_ID]);
    }

    public function test_it_allows_a_practitioner_to_update_his_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->practitioner->user);

        $parameters = [
            'status' => 'canceled',
        ];

        $response = $this->json('PATCH', "api/v1/lab/tests/{$labTest->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_tests', ['status_id' => LabTest::CANCELED_STATUS_ID]);
    }

    public function test_it_allows_a_patient_to_update_his_lab_test_status()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->patient->user);

        $parameters = [
            'status' => 'canceled',
        ];

        $response = $this->json('PATCH', "api/v1/lab/tests/{$labTest->id}", $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);

        $response->assertJsonFragment($parameters);

        $this->assertDatabaseHas('lab_tests', ['status_id' => LabTest::CANCELED_STATUS_ID]);
    }

    public function test_it_does_not_allows_a_patient_to_delete_his_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->patient->user);

        $response = $this->json('DELETE', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_practitioner_to_delete_his_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs($labTest->practitioner->user);

        $response = $this->json('DELETE', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_delete_a_lab_test()
    {
        $labTest = factory(LabTest::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $response = $this->json('DELETE', "api/v1/lab/tests/{$labTest->id}");

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }
}
