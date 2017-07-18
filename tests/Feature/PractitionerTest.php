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
            $this->assertCount(3, $response->original['data']);
        }
    }

    public function test_transformer_includes_expected_keys()
    {
        $practitioner = factory(Practitioner::class)->create([
            'rate' => 150,
        ]);

        Passport::actingAs($practitioner->user);

        $response = $this->json('GET', "api/v1/practitioners/{$practitioner->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment(['background_picture_url' => $practitioner->background_picture_url]);
        $response->assertJsonFragment(['description' => $practitioner->description]);
        $response->assertJsonFragment(['graduated_year' => (string) $practitioner->graduated_year]);
        $response->assertJsonFragment(['license_number' => (string) $practitioner->license_number]);
        $response->assertJsonFragment(['license_state' => $practitioner->license_state]);
        $response->assertJsonFragment(['license_title' => $practitioner->license_title]);
        $response->assertJsonFragment(['name' => $practitioner->user->full_name]);
        $response->assertJsonFragment(['picture_url' => $practitioner->picture_url]);
        $response->assertJsonFragment(['rate' => (string) $practitioner->rate]);
        $response->assertJsonFragment(['school' => $practitioner->school]);
        $response->assertJsonFragment(['specialty' => $practitioner->specialty]);
        $response->assertJsonFragment(['user_id' => (string) $practitioner->user_id]);
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
