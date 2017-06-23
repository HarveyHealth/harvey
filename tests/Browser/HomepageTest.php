<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomepageTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function test_it_displays_the_homepage()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Harvey');
        });
    }
}
