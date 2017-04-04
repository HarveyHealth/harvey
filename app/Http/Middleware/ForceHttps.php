<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttps
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
        if (!$request->secure() && !\App::environment('local', 'testing')) {
            return redirect()->secure($request->getRequestUri());
        }

        if (starts_with($request->root(), "https://goharvey.com")) {
            return redirect()
                ->to('https://www.goharvey.com' . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
