<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SZSZSZ3</title>
    </head>
    <body>
        <div class="container" id="app"></div>
        @viteReactRefresh
        @vite(['resources/js/App.tsx'])
    </body>
</html>
