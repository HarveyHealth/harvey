<?php

namespace Tests\Browser;

use App\Models\{User, License};
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class RegulatedStatesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function test_if_practitioner_licence_is_checked_with_zip_on_sign_up_unsuccsefull()
     {
         //$practitionerLicence = factory(License::class)->create();


         $user = factory(User::class)->make();

         $this->browse(function ($browser) use ($user) {
             $browser->visit(new SignUpPage)
                     ->addUserRegulatedStatesFail($user);


         });
     }

     public function test_if_practitioner_licence_is_checked_with_zip_on_sign_up_succsefull()
     {



         $user = factory(User::class)->make();

         $this->browse(function ($browser) use ($user) {
             $browser->visit(new SignUpPage)
                     ->addUserRegulatedStatesSuccess($user);

        $city = DB::table('users')->where('email',$user->email)->value('city');
        $state = DB::table('users')->where('email', $user->email)->value('state');
        $this->assertDatabaseHas('users', ['city' => $city]);
        $this->assertDatabaseHas('users', ['state' => $state]);


         });

     }
}
