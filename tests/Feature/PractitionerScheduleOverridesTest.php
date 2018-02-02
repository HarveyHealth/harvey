<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\PractitionerScheduleOverride;
use App\Models\Practitioner;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use ResponseCode;

class PractitionerScheduleOverridesTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Event::fake();
    }

    public function test_it_lists_out_all_overrides_for_the_practitioner()
    {
        $practitioner = factory(Practitioner::class)->create();
        factory(PractitionerScheduleOverride::class)->create([
            'practitioner_id' => $practitioner->id,
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);

        Passport::actingAs($practitioner->user);
        $response = $this->get(route('practitioner-schedule-overrides.index', [$practitioner->id]));

        $response->assertJsonFragment([
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
    }

    public function test_it_shows_a_particular_schedule_override_to_the_practitioner()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerScheduleOverride = factory(PractitionerScheduleOverride::class)->create([
            'practitioner_id' => $practitioner->id,
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);

        Passport::actingAs($practitioner->user);
        $response = $this->get(route('practitioner-schedule-overrides.show', [$practitioner->id, $practitionerScheduleOverride->id]));

        $response->assertJsonFragment([
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
    }

    public function test_a_practitioner_can_create_new_schedule_override_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $parameters = [
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->post(route('practitioner-schedule-overrides.store', $practitioner->id), $parameters);

        $response->assertJsonFragment([
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        $this->assertCount(1, PractitionerScheduleOverride::all());
    }

    public function test_a_practitioner_can_edit_schedule_override_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerScheduleOverride = factory(PractitionerScheduleOverride::class)->create([
            'practitioner_id' => $practitioner->id,
            'date' => '2018-01-01',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        $newParameters = [
            'date' => '2018-01-01',
            'start_time' => "12:00:00",
            'stop_time' => "13:30:00",
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->patch(route('practitioner-schedule-overrides.update', [$practitioner->id, $practitionerScheduleOverride->id]), $newParameters);

        $response->assertJsonFragment($newParameters);
    }

    public function test_a_practitioner_can_delete_new_schedule_override_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerScheduleOverride = factory(PractitionerScheduleOverride::class)->create(['practitioner_id' => $practitioner->id]);

        $this->assertCount(1, PractitionerScheduleOverride::all());

        Passport::actingAs($practitioner->user);
        $response = $this->delete(route('practitioner-schedule-overrides.delete', [$practitioner->id, $practitionerScheduleOverride->id]));

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
        $this->assertCount(0, PractitionerScheduleOverride::all());
    }
}
