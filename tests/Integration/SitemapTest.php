<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SitemapTest extends TestCase {

	public function testItShowsTheMainSitemapIndex()
	{
		$this->get('/sitemap.xml');

		$this->see('sitemapindex');
	}

	public function testItShowsTheBaseSitemap()
	{
		$this->get('/sitemap-base.xml');

		$this->see('sitemap');
	}
}
