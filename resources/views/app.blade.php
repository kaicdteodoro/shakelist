<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ShakeList</title>

    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
<div id="app"></div>
<footer>
    @vite('resources/js/app.js')
</footer>
</body>
</html>
