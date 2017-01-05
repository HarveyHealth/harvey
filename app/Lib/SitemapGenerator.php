<?php

namespace App\Lib;

class SitemapGenerator
{
    private $_urls = array();
    private $_base_url;

    public function __construct($base_url)
    {
        $this->_base_url = trim($base_url, ' /');
    }

    public function addPath($path, $last_modified = null, $change_frequency = 'daily')
    {
        $url = $this->_base_url . '/' . htmlentities(trim($path, ' /'));

        if (empty($last_modified)) {
            $last_modified = date('Y-m-d H:i:s');
        }

        $last_modified = date('Y-m-d', strtotime($last_modified));

        $this->_urls[] = array(
                'url' => $url,
                'last_modified' => $last_modified,
                'change_frequency' => $change_frequency
            );
    }

    public function addPaths($paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    public function urls()
    {
        return $this->_urls;
    }

    public function sitemap()
    {
        $map = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $map .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $urls = $this->orderURLs($this->urls());

        foreach ($urls as $url) {
            $map .= '<url>' . PHP_EOL;

            $map .= "\t" . '<loc>' . $url['url'] . '</loc>' . PHP_EOL;
            $map .= "\t" . '<lastmod>' . $url['last_modified'] . '</lastmod>' . PHP_EOL;
            $map .= "\t" . '<changefreq>' . $url['change_frequency'] . '</changefreq>' . PHP_EOL;

            $map .= '</url>' . PHP_EOL;
        }

        if (count($this->urls()) <= 0) {
            $map .= '<url></url>';
        }

        $map .= '</urlset>' . PHP_EOL;

        $map .= '<!-- ' . count($this->urls()) . ' URLs' . ' -->';

        return $this->output($map);
    }


    public function sitemapIndex()
    {
        $map = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $map .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $urls = $this->orderURLs($this->urls());

        foreach ($urls as $url) {
            $map .= '<sitemap>' . PHP_EOL;

            $map .= "\t" . '<loc>' . $url['url'] . '</loc>' . PHP_EOL;
            $map .= "\t" . '<lastmod>' . $url['last_modified'] . '</lastmod>' . PHP_EOL;

            $map .= '</sitemap>' . PHP_EOL;
        }

        $map .= '</sitemapindex>' . PHP_EOL;

        $map .= '<!-- ' . count($this->urls()) . ' URLs' . ' -->';

        return $this->output($map);
    }

    protected function orderURLs($urls)
    {
        usort($urls, function ($a, $b) {
                if ($a['url'] == $b['url'])
                    return 0;

                return ($a['url'] < $b['url']) ? -1 : 1;
            });

        return $urls;
    }


    public function output($map)
    {
        return $map;
    }
} // class SitemapGenerator
