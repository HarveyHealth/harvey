<?php

namespace August\Pitbull\Traits;

trait Pitbull
{
    public function addRole($role)
    {
    }

    public function removeRole($role)
    {
    }

    public function addAbility($domain_action)
    {
    }

    public function removeAbility($domain_action)
    {
    }

    public function hasRole($role)
    {
        return app()['pitbull']->userHasRole($this, $role);
    }

    public function hasPermission($domain_action)
    {
        return app()['pitbull']->userHasPermission($this, $domain_action);
    }

    public function roles()
    {
        return app()['pitbull']->rolesForUser($this);
    }

    public function permissions()
    {
        return app()['pitbull']->permissionsForUser($this);
    }

    public function flushPermissions()
    {
        app()['pitbull']->flushPermissionsForUser($this);
    }
}
