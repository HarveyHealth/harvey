<?php

namespace App\Http\Middleware;

use Closure;
use HTMLMin\HTMLMin\Minifiers\HtmlMinifier;
use Illuminate\Http\Response;

class BladeMinifier
{
    /**
     * The html minifier instance.
     *
     * @var \HTMLMin\HTMLMin\Minifiers\HtmlMinifier
     */
    protected $html;
    /**
     * Create a new minify middleware instance.
     *
     * @param \HTMLMin\HTMLMin\Minifiers\HtmlMinifier $html
     *
     * @return void
     */
    public function __construct(HtmlMinifier $html)
    {
        $this->html = $html;
    }
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
            $minified = $this->html->render($output);
            $response->setContent($minified);
        }
        return $response;
    }
    /**
     * Check if the response is a usable response class.
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isAResponseObject($response)
    {
        return is_object($response) && $response instanceof Response;
    }
    /**
     * Check if the content type header is html.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return bool
     */
    protected function isAnHtmlResponse(Response $response)
    {
        $type = $response->headers->get('Content-Type');
        return strtolower(strtok($type, ';')) === 'text/html';
    }
}
