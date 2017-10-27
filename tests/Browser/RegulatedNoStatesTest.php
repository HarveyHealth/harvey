<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\Browser\Pages\{DiscoveryPage};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegulatedNoStatesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_user_is_sent_to_no_service_page()
    {
        $user = factory(User::class)->make();

        $this->browse(function (Browser $browser) {
           $browser->visit(new DiscoveryPage)
                   ->regulatedNoStates();
          });

    }
}
