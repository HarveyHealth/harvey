<?php

namespace Tests\Browser;

use App\Models\{User,Practitioner, License};
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class SignUpRegulatedState extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_patient_from_regulated_yes_state_and_practitioner_shows_up()
    {


        $user = factory(User::class)->make();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new SignUpPage)
                    ->addUserRegulatedState($user);


        });

        

    }
}
