<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Header;

class test extends DuskTestCase
{

    public $header = new Header();
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function test_get_started_button_in_header()
     {
           $this->browse(function ($browser) {
             $browser->visit('/');
              $this->header->getStartedHeader();
                    });
     }
}
