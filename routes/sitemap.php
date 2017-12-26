<?php

/*
|--------------------------------------------------------------------------
| Sitemap Routes
|--------------------------------------------------------------------------
|
*/

// SITEMAP
Route::get('sitemap.xml', 'SitemapController@index');
Route::get('sitemap-{map?}.xml', 'SitemapController@index');
