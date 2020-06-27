<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Answer</title>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
    <div class="absolute h-full w-full flex justify-center items-center text-center text-gray-700 text-4xl px-4">
        {{ $answer }}
    </div>
    <div class=" block mx-auto text-gray-500 text-center px-4 pt-8">
        The answer's question will show up here.
    </div>
</body>
</html>
