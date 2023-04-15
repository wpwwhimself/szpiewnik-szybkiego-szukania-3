<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/sz3_olive.svg" type="image/svg+xml">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        @env("local")
        <style>
        :root{
            --acc: dodgerblue !important;
        }
        </style>
        @endenv
        <title>SZSZSZ3</title>
    </head>
    <body>
        <div class="container" id="root"></div>
        <script src="{{ mix('/js/index.js') }}"></script>
    </body>
</html>
