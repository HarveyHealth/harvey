<?php

/**
 * Pitbull configuration
 */
return [

    /*
     * Adjust this to your User model
     */
    'user_model' => 'App\Models\User',

    /*
     * The fields on the User object used to search and display.
     * The standard Laravel user has only a name field. Many change that
     * to be first_name and last_name.
     */
    'user_fields' => ['id', 'first_name', 'last_name', 'email'],

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
        'layout' => 'legacy._layouts.logged_in',
        'content' => 'content'
    ],

    /*
     * We need to use authorization middleware to prevent someone not
     * logged in from accessing the Pitbull pages. Laravel defaults to
     * an 'auth' middleware, but you can specify your preference here.
     */
    'middleware' => 'auth',
];
