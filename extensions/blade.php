<?php

// blade extensions go here

\Blade::directive('stylesheet', function ($url) {
    $id = app()->version_id;
    $string = "<link href=\"{$url}?{$id}\" rel=\"stylesheet\">";
    return $string;
});

\Blade::directive('script', function ($url) {
    $id = app()->version_id;
    $string = "<script src=\"{$url}?{$id}\" type=\"text/javascript\"></script>";
    return $string;
});

\Blade::directive('markdown', function ($view_path) {
    $file = resource_path('views' . dots_to_path($view_path)) . '.md';

    if (!file_exists($file)) {
        abort(404);
    }

    return html_from_markdown_file($file);
});
