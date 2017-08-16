<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateWebhook
{
    protected $except = ['/webhook/typeform'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((empty($key) || $key != config('webhook.key')) && !in_array($request->getPathInfo(), $this->except)) {
            abort(403, 'Not authorized');
        }

        return $next($request);
    }
}
