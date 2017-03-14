<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Practitioner;
use Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $patient;
    protected $practitioner;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->patient = factory(Patient::class)->create();
        $this->practitioner = factory(Practitioner::class)->create();

        // Create 5 past appointments for our test patient and practitioner
        factory(Appointment::class, 5)->states('past')->create([
            'patient_id' => $this->patient->id,
            'practitioner_id' => $this->practitioner->id
        ]);

        // make 1 upcoming appointment
        factory(Appointment::class)->create([
            'patient_id' => $this->patient->id,
            'practitioner_id' => $this->practitioner->id
        ]);

        // make 3 past appointments for other people
        factory(Appointment::class, 3)->states('past')->create();

        // make 5 future appointments for other people
        factory(Appointment::class, 5)->create();
    }
}
