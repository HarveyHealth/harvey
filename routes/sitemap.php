<?php

/*
|--------------------------------------------------------------------------
| Sitemap Routes
|--------------------------------------------------------------------------
|
*/

// SITEMAP
Route::group(['prefix' => '/'], function () {
    Route::get('sitemap.xml', 'SitemapController@index');
    Route::get('sitemap-{map?}.xml', 'SitemapController@index');
});
