<?php

// converts dot notation, i.e. about.index
// to a path, i.e. /about/index
function dots_to_path($dots)
{
    return '/' . str_replace('.', '/', $dots);
}

function html_from_markdown($markdown_text)
{
    $key = md5($markdown_text);

    // cache this puppy
    $html = Cache::remember($key, \Carbon::now()->addYear(1), function () use ($markdown_text) {

        // convert from markdown to text
        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($markdown_text);

        // so we get curly quotes...
        $html = str_replace('&quot;', '"', $html);

        $html = \Michelf\SmartyPants::defaultTransform($html);

        return $html;
    });

    return $html;
}

function html_from_markdown_file($file)
{
    if (!file_exists($file)) {
        abort(404);
    }

    $contents = file_get_contents($file);

    return html_from_markdown($contents);
}

if (! function_exists('api_token')) {
    
    function api_token()
    {
        if(!auth()->check()){
            return null;
        }
        
        return auth()->user()->api_token;
    }
}
