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
        $user = \Auth::user();

        if (!$user || $user->user_type != $role)
            abort(403, 'Not authorized');

        return $next($request);
    }
}
