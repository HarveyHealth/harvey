<?php

use Illuminate\Routing\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Web" middleware group. Build something great!
|
*/

// stupid blog re-routing
Route::domain('blog.goharvey.com')->group(function () {
    Route::get('/', function() {
        return redirect('/blog', 301);
    });
});

// AUTHENTICATION
Auth::routes();
Route::get('/robots.txt', 'PagesController@getRobots');
Route::get('logout', 'Auth\LoginController@logout');

// Additional routing to not use Laravel's built-in "register" route
// Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
// Route::post('signup', 'Auth\RegisterController@register');

Route::get('verify/{user_id}/{token}', 'Auth\EmailVerificationController@verify');
Route::post('verify/{user_id}/{token}', 'Auth\EmailVerificationController@setPassword');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('api/dashboard', 'DashboardController@index');

// Facebook Connect
Route::get('auth/facebook', 'Auth\AuthController@redirectToFacebookProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleFacebookProviderCallback');

// INVITE USERS
Route::get('invite', 'InviteController@getInvite');
Route::post('invite', 'InviteController@postInvite');

// TERMS
Route::get('terms', 'LegalController@terms');
Route::get('privacy', 'LegalController@privacy');

// PUBLIC BLADE PAGES
Route::get('/', 'PagesController@getHomepage')->name('home');
Route::get('about', 'PagesController@getAbout');
Route::get('/consultations', 'PagesController@getConsultations')->name('consultations');
Route::get('lab-tests/{test?}', 'PagesController@getLabTests')->name('lab-tests');

// PUBLIC VIEW PAGES
Route::get('/get-started', 'GetStartedController@index')->name('getstarted');

// INTAKE
Route::get('/intake', 'IntakeController@index')->name('intake');

if (isLocal()) {
	Route::get('test', 'TestController@index');
}
