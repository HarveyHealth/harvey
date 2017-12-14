<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Lib\SitemapGenerator;
use App\Lib\TimeInterval;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index($map = null)
    {
        $key = 'sitemap-' . $map;

        $self = $this;

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
        $map->addPath('sitemap-users.xml');
        $map->addPath('blog/sitemap_index.xml');

        return $map->sitemapIndex();
    }

    private function base()
    {
        $base_paths = [
            '',
            'login',
            'signup',
            'about',
            'terms',
            'privacy',
            'blog',
        ];

        asort($base_paths);

        $map = new SitemapGenerator(url(config('app.url')));
        $map->addPaths($base_paths);

        return $map->sitemap();
    }

    private function users()
    {
        $map = new SitemapGenerator(url(config('app.url')));

        foreach ($this->users as $user) {
            $map->addPath('users/' . $user->id);
        }

        return $map->sitemap();
    }

    private function output($output)
    {
        return response($output, 200)->header('Content-Type', 'text/plain');
    }
}
