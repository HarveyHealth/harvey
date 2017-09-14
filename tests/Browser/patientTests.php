<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{User, Patient, Admin, Practitioner, Appointment, LabOrder, Message, License, LabTest};
use Tests\Browser\Pages\DashboardPage;
use Illuminate\Support\Facades\DB;

class patientTests extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_user_can_login_and_see_dashboard()
    {
        $appointment = factory(Appointment::class)->create();
        //dd($appointment->appointment_at);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->waitForText('UPCOMING APPOINTMENTS')
                    ->assertSee('UPCOMING APPOINTMENTS');
        });
    }

    public function test_if_user_can_login_and_see_appointment_page()
    {
        $appointment = factory(Appointment::class)->create();
        //dd($appointment->appointment_at);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->pause(2000)
                    ->waitFor('@patientApp')
                    ->click('@patientApp')
                    ->waitFor('@patientAppTab')
                    ->click('@patientAppTab')
                    ->waitForText('Update Appointment')
                    ->assertSee('Update Appointment');
        });
    }

    public function test_if_user_can_login_and_see_labOrders_page()
    {
        $appointment = factory(Appointment::class)->create();
                       factory(LabOrder::class)->create([
                         "patient_id" => '1',
                         "practitioner_id" => '1'
                       ]);
                       factory(LabTest::class)->create([
                         'lab_order_id' => '1'
                       ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->waitFor('@patientLab')
                    ->click('@patientLab')
                    ->waitForText('ORDER DATE')
                    ->assertSee('ORDER DATE');
        });
    }

    public function test_if_user_can_login_and_see_messages_page()
    {
        $appointment = factory(Appointment::class)->create();
                       factory(Message::class)->create([
                        "sender_user_id" => '1',
                        "recipient_user_id" => '2',
                        "subject" => 'Hi!'

                       ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->waitFor('@patientMessage')
                    ->click('@patientMessage')
                    ->waitForText('Hi!')
                    ->assertSee('Hi!');
        });
    }

    public function test_if_user_can_login_and_see_settings_page_and_add_a_cc()
    {
        $appointment = factory(Appointment::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->waitFor('@patientSettings')
                    ->click('@patientSettings')
                    ->waitForText('PAYMENT DETAILS')
                    ->assertSee('PAYMENT DETAILS')
                    ->click('@addCard')
                    ->addCard();
        });
    }

}
