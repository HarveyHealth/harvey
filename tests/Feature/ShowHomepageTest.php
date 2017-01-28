<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShowHomepageTest extends TestCase
{
    public function testItShowsTheHomepage()
    {
        $this->visit("/")->see("Welcome");
    }
}
