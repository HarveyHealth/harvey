<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Not authorized');
        }

        $roles = explode('|', $role);

        $allowed = false;

        foreach ($roles as $role) {
            if ($user->type == $role) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            abort(403, 'Not authorized');
        }

        return $next($request);
    }
}
