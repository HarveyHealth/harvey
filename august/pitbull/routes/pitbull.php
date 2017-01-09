<?php

// Configure the routes for Pitbull
Route::group(['prefix' => 'pitbull', 'middleware' => ['web']], function () {

    Route::get('', '\August\Pitbull\PitbullController@getUsers');
    Route::get('/user/{id}', '\August\Pitbull\PitbullController@getUser');

    Route::get('roles', '\August\Pitbull\PitbullController@getRoles');
    Route::post('roles', '\August\Pitbull\PitbullController@postRoles');

    // Ajax routes
    Route::get('rolesforuser/{id}', '\August\Pitbull\PitbullController@getRolesForUser');
    Route::get('permissionsforuser/{id}', '\August\Pitbull\PitbullController@getPermissionsForUser');
    Route::get('searchroles', '\August\Pitbull\PitbullController@getSearchRoles');
    Route::get('searchpermissions/{id}', '\August\Pitbull\PitbullController@getSearchPermissions');
});
