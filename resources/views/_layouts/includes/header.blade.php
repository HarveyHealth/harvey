<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (View::hasSection('page_title'))
        <title>@yield('page_title') :: {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    {{-- CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> {{-- switch to custom build icons later --}}
    @stylesheet(/css/app.css)
    @stack('stylesheets')

</head>
<body class="{{ collect(\Request::segments())->implode('-') }}@yield('body_class')">

    @include('_layouts.includes.messages')
