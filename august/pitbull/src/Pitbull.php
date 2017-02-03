<?php

namespace August\Pitbull;

use Carbon\Carbon;

class Pitbull
{
    public function userHasRole($user, $role)
    {
        return (in_array($role, array_keys($this->rolesForUser($user))));
    }

    public function userHasPermission($user, $domain, $action)
    {
        $permissions = $this->permissionsForUser($user);

        $domain_permissions = $permissions[$domain];

        if (!empty($domain_permissions)) {
            if ($action == '*') {
                return true;
            }

            if (isset($domain_permissions[$action])) {
                return true;
            }

            if (isset($domain_permissions['*'])) {
                return true;
            }
        }

        return false;
    }

    public function permissionsForUser($user)
    {
        $grid = $this->permissionGridForUser($user);
        return $grid['permissions'];
    }

    public function rolesForUser($user)
    {
        $grid = $this->permissionGridForUser($user);
        return $grid['roles'];
    }

    protected function permissionGridForUser($user)
    {
        $pitbull = $this;
        $grid = \Cache::remember($this->cacheKeyForUser($user), Carbon::now()->addYear(1), function () use ($user, $pitbull) {
            $grid = [];

            // get any role-based permissions
            $user_roles = PBRoleUser::join('pb_roles', 'pb_roles.id', '=', 'rb_role_user.role_id')
                                    ->join('pb_permissions', 'pb_permissions.role_id', '=', 'pb_roles.id')
                                    ->where('pb_role_user.user_id', $user->id);

            foreach ($user_roles as $role) {
                $grid = $this->addRoleToGrid($grid, $role);
            }

            // add any user-based permissions
        });

        return $grid;
    }

    protected function addRoleToGrid($grid, $role)
    {
    }

    public function flushPermissoinsForUser($user)
    {
        \Cache::forget($this->cacheKeyForUser($user));
    }

    protected function cacheKeyForUser($user)
    {
        return 'Pitbull:permission_grid:' . $user->id;
    }
}
