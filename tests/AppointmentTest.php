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
        factory(Appointment::class, 2)->states('past')->create([
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
        factory(Appointment::class, 5)->states('past')->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_scopes_upcoming_correctly()
    {
        $upcoming = Appointment::upcoming();

        $this->assertEquals($upcoming->count(), 1);
    }
}
