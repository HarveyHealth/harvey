<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Practitioner;
use function factory;
use Laravel\Passport\Passport;
use Tests\TestCase;
use ResponseCode;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SkusTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function it_only_allows_an_admin_to_create_a_new_sku()
    {
        $payload = [
            'name' => 'Microsample',
            'price' => 100.00,
            'cost' => 50.00,
            'description' => 'some description',
            'image' => 'some/image',
            'sample' => 'Blood draw',
            'quote' => 'Some quote here.',
            'lab_name' => 'Quest',
            'published_at' => true,
        ];
        
        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);
        $response = $this->json('POST', route('skus.store'), $payload);
    
        $response->assertStatus(ResponseCode::HTTP_OK);
        
        $practitioner = factory(Practitioner::class)->create();
        Passport::actingAs($practitioner->user);
        $response = $this->json('POST', route('skus.store'), $payload);
    
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    
    }
}
