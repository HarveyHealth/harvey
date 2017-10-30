<?php

namespace Tests\Browser;

use App\Models\{User,Practitioner, License};
use Tests\Browser\Pages\{SignUpPage, DiscoveryPage};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class SignUpFlowUnregulated extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
     //test below checks that all practitioners are showin in the signup flow


    public function test_patient_from_unregulated_state()
    {


        $user = factory(User::class)->make();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new DiscoveryPage)
                    ->arrayPractitioners()
                    ->addUserUnregulatedStates($user)
                    ->click('@letsGo');



         $first_name = DB::table('users')->where('id', 1)->value('first_name');
         $last_name = DB::table('users')->where('id', 1)->value('last_name');
         $first_nameTwo = DB::table('users')->where('id', 3)->value('first_name');
         $last_nameTwo = DB::table('users')->where('id', 3)->value('last_name');

         $full_name = "Dr. " .$first_name . ' ' . $last_name . ',';
         $full_nameTwo = "Dr. " .$first_nameTwo . ' ' . $last_nameTwo . ',';

            $browser->waitForText($full_name)
                    ->assertSee($full_name);

            });

          }
}
