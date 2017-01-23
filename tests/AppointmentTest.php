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

    function setUpDB()
    {
        if ($this->patient)
            return;

        $this->patient = factory(User::class)->make();
        $this->practitioner = factory(User::class)->make();

        // Create 5 past appointments for our test patient and practitioner
        factory(Appointment::class, 2)->states('past')->make([
            'patient_user_id' => $this->patient->id,
            'practitioner_user_id' => $this->practitioner->id
        ]);

        // make 1 upcoming appointment
        factory(Appointment::class)->make([
            'patient_user_id' => $this->patient->id,
            'practitioner_user_id' => $this->practitioner->id
        ]);

        // make 3 past appointments for other people
        factory(Appointment::class, 3)->states('past')->make();

        // make 5 future appointments for other people
        factory(Appointment::class, 5)->states('past')->make();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_scopes_upcoming_correctly()
    {
        $this->setUpDB();

        $upcoming = Appointment::upcoming();

        $this->assertEquals($upcoming->count(), 6);
    }
}
