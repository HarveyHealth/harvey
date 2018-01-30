<?php

namespace App\Lib;

use HTMLMin\HTMLMin\Http\Middleware\MinifyMiddleware;
use Closure;

class HarveyMinifyMiddleware extends MinifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->isAResponseObject($response) && $this->isAnHtmlResponse($response)) {
            $output = $response->getContent();
            $minified = preg_replace('/>(\s+)</', '><', str_replace("\n", ' ', $this->html->render($output)));
            $response->setContent($minified);
        }

        return $response;
    }
}