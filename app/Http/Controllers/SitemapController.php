<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Lib\SitemapGenerator;
use App\Lib\TimeInterval;
use Illuminate\Support\Facades\Cache;
use App\Models\SKU;
use App\Models\Condition;

class SitemapController extends Controller
{
    protected $skus;
    protected $conditions;

    function __construct(SKU $skus, Condition $conditions)
    {
        $this->skus = $skus;
        $this->conditions = $conditions;
    }

    public function index($map = null)
    {
        // process the map key so that PHP doesn't
        // choke on function names with hyphens
        $map = str_replace('-', ' ', $map);
        $map = camel_case($map);

        $key = 'sitemap-' . $map;

        $self = $this;

        // cache the map output so we're not searching every time
        // cache is limited to 1 day
        $output = Cache::remember($key, TimeInterval::days(1)->toMinutes(), function () use ($map, $self) {
            // if it's a map, call the
            // method for the particular map
            if ($map) {

                // make sure the method exists
                if (!method_exists($self, $map)) {
                    return abort(404);
                }

                return $self->$map();
            }

            return $self->base_index();
        });

        return $this->output($output);
    }

    private function base_index()
    {
        $map = new SitemapGenerator(url(config('app.url')));

        // add the various indexes
        // you must have a method for each index that
        // outputs the appropriate sitemap
        // in this case, sitemap-base.xml requires
        // a method called 'base' which is below
        $map->addPath('sitemap-base.xml');
        $map->addPath('sitemap-lab-tests.xml');
        $map->addPath('sitemap-conditions.xml');

        // now add the blog sitemap indexes
        $map->addPath('blog/post-sitemap.xml');
        $map->addPath('blog/category-sitemap.xml');

        return $map->sitemapIndex();
    }

    private function base()
    {
        $base_paths = [
            '',
            'login',
            'about',
            'financing',
            'get-started',
        ];

        // sort 'em if you got 'em
        asort($base_paths);

        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPaths($base_paths);

        return $map->sitemap();
    }

    private function labTests()
    {
        $tests = $this->skus->labtests()->get();

        $map = new SitemapGenerator(url(config('app.url')));

        $map->addPath('lab-tests');

        foreach ($tests as $test) {
            $map->addPath('lab-tests/' . $test->slug);
        }

        return $map->sitemap();
    }

    private function conditions()
    {
        $conditions = $this->conditions->all();

        $map = new SitemapGenerator(url(config('app.url')));

        $map->addPath('conditions');

        foreach ($conditions as $condition) {
            $map->addPath('conditions/' . $condition->slug . '#/');
        }

        return $map->sitemap();
    }

    private function output($output)
    {
        return response($output, 200)->header('Content-Type', 'text/plain');
    }
}
