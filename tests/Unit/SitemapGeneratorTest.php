<?php

namespace Tests\Unit;

use App\Lib\SitemapGenerator;
use Tests\TestCase;


class AppointmentScopeTest extends TestCase
{

    function testAddPath()
    {
        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPath('conditions');

        $urls = $map->urls();

        $this->assertCount(1, $urls);
        $this->assertEquals(url(config('app.url')) + '/conditions',$urls[0]['url']);
    }

    function testAddPaths()
    {
        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPaths([
            'test',
            'test',
            'test',
        ]);

        $map->addPath('conditions');

        $urls = $map->urls();

        $this->assertCount(4, $urls);
    }


    function testSitemap()
    {
        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPaths([
            'test1',
            'test2',
            'test3',
        ]);

        $xml = new \SimpleXMLElement($map->sitemap());

        $this->assertNotFalse($xml);
        $this->assertEquals(3, count($xml->url->children()));
    }
}
