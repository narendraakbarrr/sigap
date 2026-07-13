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
    <body class="font-sans antialiased bg-slate-950 text-slate-900">
        <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-10 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(53,224,224,0.18),_transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(217,79,176,0.18),_transparent_30%),linear-gradient(135deg,#3a6df0_0%,#5b5ce8_35%,#7c4fe0_60%,#b23fbf_85%,#d94fa8_100%)]"></div>
            <div class="absolute -top-24 left-1/2 h-72 w-72 rounded-full bg-cyan-300/20 blur-3xl xl:-left-10"></div>
            <div class="absolute -bottom-24 right-1/2 h-80 w-80 rounded-full bg-pink-400/20 blur-3xl xl:bottom-8 xl:right-16"></div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 border border-slate-200 shadow-xl shadow-slate-400/10 overflow-hidden rounded-[2rem]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
