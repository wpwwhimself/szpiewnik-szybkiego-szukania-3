<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('sz3_olive.svg') }}" type="image/svg+xml">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        @env("local")
        <style>
        :root{
            --acc: dodgerblue !important;
        }
        </style>
        @endenv
        <title>{{ $title ? "$title | " : "" }}{{ env("APP_NAME") }}</title>
    </head>
    <body>
        <x-header title="{{ $title }}" />
        <div id="main-wrapper">
            @yield("content")
        </div>
        <x-footer />
    </body>
</html>
