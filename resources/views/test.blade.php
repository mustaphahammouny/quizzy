<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    <meta name="description"
        content="OneUI Vue Edition is a Bootstrap 5 Admin Template &amp; UI Framework based on Vue.js 3. Created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="{{ asset('/assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ asset('/assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('/assets/media/favicons/apple-touch-icon-180x180.png') }}">
</head>

<body>
    @if (Auth::check())
        <p>{{ Auth::user()->full_name }}</p>
    @else
        <p>Guest</p>
    @endif
</body>

</html>
