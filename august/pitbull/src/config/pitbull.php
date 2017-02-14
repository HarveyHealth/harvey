<?php

/**
 * Pitbull configuration
 */
return [

    /*
     * Adjust this to your User model
     */
    'user_model' => 'App\User',

    /*
     * There are the fields on the user object to
     * display when viewing the list of users. Laravel, for example,
     * defaults to just 'name', but many change that to 'first_name' and
     * 'last_name'. Feel free to change that here.
     */
    'user_fields' => ['id', 'name', 'email'],

    /*
     * These permissions determine which users may update roles,
     * permissions, and the user roles and permissions.
     *
     * At first, you'll want to leave these as is, so you can
     * set up the initial roles and permissions.
     *
     * Once you've done that, modify these, so that only the Users
     * you choose may make changes.
     */
    'permissions' => [
        'can_add_roles' => '*',
        'can_delete_roles' => '*',
        'can_add_permissions' => '*',
        'can_delete_permissions' => '*',
        'can_assign_roles' => '*',
        'can_assign_permissios' => '*',
    ],

    /*
     * Layout to use and the content block. By default Laravel uses
     * 'layouts.app' and 'content', but you can specify your
     * preference here.
     */
    'templates' => [
        'layout' => 'layouts.app',
        'content' => 'content'
    ],

    /*
     * We need to use authorization middleware to prevent someone not
     * logged in from accessing the Pitbull pages. Laravel defaults to
     * an 'auth' middleware, but you can specify your preference here.
     */
    'middleware' => 'auth',
];
