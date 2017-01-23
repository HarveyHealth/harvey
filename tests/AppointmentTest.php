<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Models\Appointment;

class AppointmentTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $patient;
    protected $practitioner;

    function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->patient = factory(User::class)->create();
        $this->practitioner = factory(User::class)->create();

        // Create 5 past appointments for our test patient and practitioner
        factory(Appointment::class, 5)->states('past')->create([
            'patient_user_id' => $this->patient->id,
            'practitioner_user_id' => $this->practitioner->id
        ]);

        // make 1 upcoming appointment
        factory(Appointment::class)->create([
            'patient_user_id' => $this->patient->id,
            'practitioner_user_id' => $this->practitioner->id
        ]);

        // make 3 past appointments for other people
        factory(Appointment::class, 3)->states('past')->create();

        // make 5 future appointments for other people
        factory(Appointment::class, 5)->create();
    }

    public function test_it_scopes_upcoming_correctly()
    {
        $upcoming = Appointment::upcoming();

        // this tests for all upcoming appointments
        $this->assertEquals($upcoming->count(), 6);

        // this tests for upcoming appointments for our patient
        $upcoming_for_patient = $upcoming->forPatient($this->patient->id);

        $this->assertEquals($upcoming_for_patient->count(), 1);

        // this tests for upcoming appointments for our practitioner
        $upcoming_for_practitioner = $upcoming->forPractitioner($this->practitioner->id);

        $this->assertEquals($upcoming_for_practitioner->count(), 1);
    }

    public function test_it_scopes_recent_correctly()
    {
        $recent = Appointment::recent(50);

        // this tests for all recent appointments
        $this->assertEquals($recent->count(), 8);

        // this tests for recent appointments for our patient
        $recent_for_patient = $recent->forPatient($this->patient->id);

        $this->assertEquals($recent_for_patient->count(), 5);

        // this tests for recent appointments for our practitioner
        $recent_for_practitioner = $recent->forPractitioner($this->practitioner->id);

        $this->assertEquals($recent_for_practitioner->count(), 5);
    }
}
