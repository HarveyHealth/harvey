<?php

namespace Tests\Browser;

use App\Models\{User, License};
use Tests\Browser\Pages\{SignUpPage, DiscoveryPage};
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
         $user = factory(User::class)->create();


         $this->browse(function ($browser) use ($user) {
             $browser->visit(new DiscoveryPage)
                     ->addUserRegulatedStatesFail($user);


         });
     }

     public function test_if_practitioner_licence_is_checked_with_zip_on_sign_up_succsefull()
     {



         $user = factory(User::class)->make();

         $this->browse(function ($browser) use ($user) {
             $browser->visit(new DiscoveryPage)
                     ->addUserRegulatedStatesSuccess($user);


            });

     }
}
