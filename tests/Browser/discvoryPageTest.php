<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\DiscoveryPage;



class discvoryPageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_allergies_flow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new DiscoveryPage)
                    ->allergies();
        });
    }
}
