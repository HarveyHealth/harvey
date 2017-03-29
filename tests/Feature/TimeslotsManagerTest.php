<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Lib\TimeslotManager;

class TimeslotsManagerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_gets_timeslot_id_for_day_and_time()
    {
        $manager = new TimeslotManager;

        // right on the :00
        $id = $manager->timeslotForDayAndTime('Monday', '11:00');
        $this->assertEquals($id, 71);

        // at the :15
        $id = $manager->timeslotForDayAndTime('Monday', '11:15');
        $this->assertEquals($id, 71);

        // right on the :00
        $id = $manager->timeslotForDayAndTime('Saturday', '11:00');
        $this->assertEquals($id, 311);

        // at the :15
        $id = $manager->timeslotForDayAndTime('Saturday', '11:15');
        $this->assertEquals($id, 311);
    }
}
