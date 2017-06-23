<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SitemapTest extends DuskTestCase
{
    public function test_it_displays_the_main_sitemap_index()
    {
        $this->browse(function ($browser) {
            $browser->visit('/sitemap.xml')
                    ->assertSee('sitemapindex');
        });
    }
    
    public function test_it_displays_the_base_sitemap()
    {
        $this->browse(function ($browser) {
            $browser->visit('/sitemap-base.xml')
                ->assertSee('sitemap');
        });
    }
}
