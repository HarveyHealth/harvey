<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ResponseCode;

class SitemapTest extends TestCase
{
    public function test_sitemap_xml_returns_ok()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $parsedResponse = (array) simplexml_load_string($response->original);

        $this->assertCount(4, $parsedResponse['sitemap']);
    }

    public function test_sitemap_base_xml_returns_ok()
    {
        $response = $this->get('/sitemap-base.xml');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $parsedResponse = (array) simplexml_load_string($response->original);

        $this->assertCount(6, $parsedResponse['url']);
    }

    public function test_sitemap_users_xml_returns_ok()
    {
        $response = $this->get('/sitemap-users.xml');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $parsedResponse = (array) simplexml_load_string($response->original);

        $this->assertInstanceOf('SimpleXMLElement', $parsedResponse['url']);
    }

    public function test_invalid_sitemap_returns_404()
    {
        $response = $this->get('/sitemap-invalid.xml');
        $response->assertStatus(ResponseCode::HTTP_NOT_FOUND);
    }

}
