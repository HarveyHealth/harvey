<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnregulatedStatesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function test_if_a_user_is_serviced_due_to_unregulated_state_zipcode()
     {
         $user = factory(User::class)->make();

         $this->browse(function ($browser) use ($user) {
             $browser->visit(new SignUpPage)
                     ->addUserUnregulatedStates($user);


         });
     }
}
