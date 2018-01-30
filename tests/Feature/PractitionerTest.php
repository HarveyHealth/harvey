<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Patient;
use App\Models\Practitioner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PractitionerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_anyone_can_view_practitioner_list()
    {
        factory(Practitioner::class, 3)->create();

        foreach ([Patient::class, Admin::class, Practitioner::class] as $userClass) {
            Passport::actingAs(factory($userClass)->make()->user);
            $response = $this->json('GET', 'api/v1/practitioners/');
            $response->assertStatus(200);
            $this->assertCount(3, $response->original->data);
        }
    }

    public function test_transformer_includes_expected_keys()
    {
        $practitioner = factory(Practitioner::class)->create();

        Passport::actingAs($practitioner->user);

        $response = $this->json('GET', "api/v1/practitioners/{$practitioner->id}");

        $response->assertJsonFragment(['name' => $practitioner->user->full_name]);
        $response->assertJsonFragment(['type_name' => $practitioner->type->name]);
        $response->assertJsonFragment(['user_id' => (string) $practitioner->user_id]);
        $response->assertStatus(200);
    }

    public function test_anyone_can_view_practitioner_availability()
    {
        factory(Practitioner::class, 3)->create();

        foreach ([Patient::class, Admin::class, Practitioner::class] as $userClass) {
            Passport::actingAs(factory($userClass)->make()->user);
            $response = $this->json('GET', 'api/v1/practitioners/'.Practitioner::first()->id.'?include=availability');
            $response->assertJsonFragment(['meta' => ['availability' => []]]);
            $response->assertStatus(200);
        }
    }
}
