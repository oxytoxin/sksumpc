<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>
        @env('local')
        <div class="space-y-2">
            <x-login-link email="sksumpcadmin@gmail.com" label="Login as manager" />
            <x-login-link email="sksumpccashier@gmail.com" label="Login as cashier" />
            <x-login-link email="sksumpccbu@gmail.com" label="Login as cbu" />
            <x-login-link email="sksumpcmso@gmail.com" label="Login as mso" />
            <x-login-link email="sksumpcloan@gmail.com" label="Login as loan" />
            <x-login-link email="sksumpcbookkeeper@gmail.com" label="Login as bookkeeper" />
        </div>
        @endenv
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
