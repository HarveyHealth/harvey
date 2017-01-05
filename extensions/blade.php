<?php

// blade extensions go here

\Blade::directive('stylesheet', function ($url) {
    $string = "<link href=\"{$url}\" rel=\"stylesheet\">";
    return $string;
});

\Blade::directive('script', function ($url) {
    $string = "<script src=\"{$url}\" type=\"javascript\"></script>";
    return $string;
});
