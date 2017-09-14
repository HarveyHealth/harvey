<?php

namespace Tests\Browser;

use App\Models\{User,Practitioner, License};
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class SignUpRegulatedStateNo extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.un
     *
     * @return void
     */
    public function test_patient_from_regulated_yes_state_and_practitioner_does_not_show_up()
    {


        $user = factory(User::class)->make();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new SignUpPage)
                    ->preRegulatedNoFlow()
                    ->addUserNo($user);

                  });



    }
}
