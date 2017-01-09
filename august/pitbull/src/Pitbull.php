<?php

namespace August\Pitbull;

class Pitbull
{
    public function permissionGridForUser($user) {

        $grid = \Cache::remember($this->cacheKeyForUser($user), \Carbon::now()->addYear(1), function () {

        });

        return $grid;
    }

    protected function flushCacheForUser($user) {
        \Cache::forget($this->cacheKeyForUser($user));
    }

    protected function cacheKeyForUser($user) {
        return 'Pitbull:permission_grid:' . $user->id;
    }

    public function userForUserID($user_id)
    {
        $class = $this->user_model;
        $user = $class::findOrFail($user_id);
        return $user;
    }
}
