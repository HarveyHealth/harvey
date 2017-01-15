<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $request->input('key');

        if (empty($key) || $key != config('webhook.key')) {
            abort(403, 'Not authorized');
        }

        return $next($request);
    }
}
