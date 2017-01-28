<?php

namespace Tests\Integration;

use Tests\TestCase;

class ShowHomepageTest extends TestCase
{
    public function testItShowsTheHomepage()
    {
        $this->visit("/")->see("Welcome");
    }
}
