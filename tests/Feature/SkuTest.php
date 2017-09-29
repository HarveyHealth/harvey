<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use ResponseCode;
use Tests\TestCase;

class SkuTest extends TestCase
{
    use DatabaseMigrations;

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
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('skus', ['name' => 'Test', 'price' => 200.00]);
    }
}
