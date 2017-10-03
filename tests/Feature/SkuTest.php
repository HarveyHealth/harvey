<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LabTestInformation;
use App\Models\Practitioner;
use App\Models\Patient;
use App\Models\SKU;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use ResponseCode;
use Tests\TestCase;

class SkuTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_only_admins_can_create_a_new_sku()
    {
    
        $patient = factory(Patient::class)->create();
        Passport::actingAs($patient->user);
    
        $response = $this->post(route('skus.store'), [
            'name' => 'Test',
            'price' => 200.00,
            'description' => '<p>Hello World</p>',
            'image' => '/images/lab_tests/hormones.png',
            'sample' => 'Blood draw',
            'quote' => 'Take this test!',
            'lab_name' => 'Unknown',
        ]);
    
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        
        $practitioner = factory(Practitioner::class)->create();
        Passport::actingAs($practitioner->user);

        $response = $this->post(route('skus.store'), [
            'name' => 'Test',
            'price' => 200.00,
            'description' => '<p>Hello World</p>',
            'image' => '/images/lab_tests/hormones.png',
            'sample' => 'Blood draw',
            'quote' => 'Take this test!',
            'lab_name' => 'Unknown',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_creates_a_new_sku_object_in_the_database()
    {
        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        
        $response = $this->post(route('skus.store'), [
            'name' => 'Test',
            'price' => 200.00,
            'description' => '<p>Hello World</p>',
            'image' => '/images/lab_tests/hormones.png',
            'sample' => 'Blood draw',
            'quote' => 'Take this test!',
            'lab_name' => 'Unknown',
        ]);
        
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('skus', ['name' => 'Test', 'price' => 200.00]);
    }
    
    public function test_it_shows_a_specific_sku_and_related_lab_test_information()
    {
        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);

        $sku = factory(SKU::class)->states('lab-test')->create(['name' => 'somename']);
        $sku->labTestInformation()->save(factory(LabTestInformation::class)->make());

        $response = $this->get(route('skus.show', $sku->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'somename']);
    }
    
    public function test_it_allows_an_admin_to_modify_a_sku_and_lab_information()
    {
        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
    
        $sku = factory(SKU::class)->states('lab-test')->create(['name' => 'somename']);
        $sku->labTestInformation()->save(factory(LabTestInformation::class)->make());
    
        $response = $this->put(route('skus.update', $sku->id), [
            'name' => 'someothername',
            'price' => 200.00,
            'description' => '<p>Hello World</p>',
            'image' => '/images/lab_tests/hormones.png',
            'sample' => 'Blood draw',
            'quote' => 'Take this test!',
            'lab_name' => 'Unknown',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'someothername']);
    }
}
