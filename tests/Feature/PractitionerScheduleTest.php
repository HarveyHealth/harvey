<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Practitioner;
use App\Models\PractitionerSchedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use ResponseCode;
use Tests\TestCase;

class PractitionerScheduleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Event::fake();
    }

    public function test_a_practitioner_can_view_one_of_their_available_schedules()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerSchedule = factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);

        Passport::actingAs($practitioner->user);
        $response = $this->get(route('practitioner-schedule.show', [$practitioner->id, $practitionerSchedule->id]));

        $response->assertJsonFragment([
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
    }

    public function test_a_practitioner_can_view_all_of_their_available_schedules()
    {
        $practitioner = factory(Practitioner::class)->create();
        factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => 'Wednesday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);

        Passport::actingAs($practitioner->user);
        $response = $this->get(route('practitioner-schedule.index', $practitioner->id));

        $response->assertJsonFragment([
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        $response->assertJsonFragment([
            'day_of_week' => 'Wednesday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
    }

    public function test_a_patient_cannot_view_the_practitioner_schedule_directly()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();
        factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);

        Passport::actingAs($patient->user);
        $response = $this->get(route('practitioner-schedule.index', $practitioner->id));
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_a_practitioner_can_create_new_schedule_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $parameters = [
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->post(route('practitioner-schedule.store', $practitioner->id), $parameters);

        $response->assertJsonFragment([
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        $this->assertCount(1, PractitionerSchedule::all());
    }

    public function test_a_practitioner_can_edit_schedule_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerSchedule = factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => 'Monday',
            'start_time' => "11:00:00",
            'stop_time' => "12:30:00",
        ]);
        $newParameters = [
            'day_of_week' => 'Monday',
            'start_time' => "12:00:00",
            'stop_time' => "01:30:00",
        ];

        Passport::actingAs($practitioner->user);
        $response = $this->patch(route('practitioner-schedule.update', [$practitioner->id, $practitionerSchedule->id]), $newParameters);

        $response->assertJsonFragment($newParameters);
    }

    public function test_a_practitioner_can_delete_new_schedule_rows()
    {
        $practitioner = factory(Practitioner::class)->create();
        $practitionerSchedule = factory(PractitionerSchedule::class)->create(['practitioner_id' => $practitioner->id]);

        $this->assertCount(1, PractitionerSchedule::all());

        Passport::actingAs($practitioner->user);
        $response = $this->delete(route('practitioner-schedule.delete', [$practitioner->id, $practitionerSchedule->id]));

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
        $this->assertCount(0, PractitionerSchedule::all());
    }
}
