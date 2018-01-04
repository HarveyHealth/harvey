<?php

namespace Tests\Unit;

use App\Lib\SitemapGenerator;
use Tests\TestCase;
use SimpleXMLElement;

class SitemapGeneratorTest extends TestCase
{
    public function test_add_path()
    {
        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPath('conditions');

        $urls = $map->urls();

        $this->assertCount(1, $urls);
        $this->assertEquals(url(config('app.url')) . '/conditions', $urls[0]['url']);
    }

    public function test_add_paths()
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

    public function test_sitemap()
    {
        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPaths([
            'test1',
            'test2',
            'test3',
        ]);

        $xml = new SimpleXMLElement($map->sitemap());

        $this->assertNotFalse($xml);
        $this->assertEquals(3, count($xml->url->children()));
    }
}
