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

function phone_for_db($number)
{
    return phone($number, 'US', 'NATIONAL');
}

function ip_address()
{
    // cloudflare
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

    //check ip from share internet
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];

    //to check ip is pass from proxy
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function currentUser()
{
    return auth()->user();
}

function isLocal()
{
    return app()->environment('local');
}

function isProd()
{
    return app()->environment('production');
}

function isNotProd()
{
    return !isProd();
}

function isStgOrProd()
{
    return app()->environment('staging', 'production');
}

function isNotStgOrProd()
{
    return !isStgOrProd();
}

function ops_message($level, $alert, $message, $channels = 'engineering')
{
    if (!empty($alert)) {
        $alert = ucwords($alert);
        $message = "*[{$alert}]* " . $message;
    }

    foreach ((array) $channels as $channel) {
        (new \App\Lib\Slack)->notify(new \App\Notifications\SlackNotification($message, $channel, $level));
    }
}

function ops_info($alert, $message, $channels = 'engineering')
{
    ops_message('info', $alert, $message, $channels);
}

function ops_warning($alert, $message, $channels = 'engineering')
{
    ops_message('warning', $alert, $message, $channels);
}

function ops_error($alert, $message, $channels = 'engineering')
{
    ops_message('error', $alert, $message, $channels);
}

function ops_success($alert, $message, $channels = 'engineering')
{
    ops_message('success', $alert, $message, $channels);
}

function cloudfront_link(string $path)
{
    if (!starts_with($path, '/')) {
        $path = "/{$path}";
    }

    return 'https://' . config('filesystems.cloudfront.domain') . $path;
}

function log_mark()
{
    static $log_mark = 0;

    $log_letters = range('a','z');
    $marker = $log_letters[$log_mark];
    \Log::info($marker);
    $log_mark++;
}

function cast_to_string($value)
{
    return empty($value) ? null : (string) $value;
}