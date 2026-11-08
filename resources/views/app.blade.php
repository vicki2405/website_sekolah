<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        @php
            $schoolFavicon = \Illuminate\Support\Facades\Cache::remember('public_site_favicon', now()->addHours(24), function () {
                try {
                    return \App\Models\Setting::where('key', 'school_favicon')->value('value');
                } catch (\Exception) {
                    return null;
                }
            });
        @endphp
        @if($schoolFavicon)
            <link rel="icon" href="{{ $schoolFavicon }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
