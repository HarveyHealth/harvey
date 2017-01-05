<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowHomepageTest extends TestCase {

	public function testItShowsTheHomepage()
	{
		$this->visit("/")->see("Welcome");
	}
}
