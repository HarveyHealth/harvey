<?php

// blade extensions go here

\Blade::directive('stylesheet', function ($url) {
    $string = "<link href=\"{$url}\" rel=\"stylesheet\">";
    return $string;
});

\Blade::directive('script', function ($url) {
    $string = "<script src=\"{$url}\" type=\"text/javascript\"></script>";
    return $string;
});

\Blade::directive('markdown', function ($view_path) {
    $file = resource_path('views' . dots_to_path($view_path)) . '.md';

    if (!file_exists($file)) {
        abort(404);
    }

    return html_from_markdown_file($file);
});
