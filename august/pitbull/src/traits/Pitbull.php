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

    public function addAbility($domain, $action)
    {
    }

    public function removeAbility($domain, $action)
    {
    }

    public function hasRole($role)
    {
        return (in_array($role, array_keys($this->roles())));
    }

    public function hasPermission($domain, $action)
    {
        return (in_array($role, array_keys($this->permissions())));
    }

    public function roles()
    {
    }

    public function permissions()
    {
    }
}
