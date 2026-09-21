<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('clinic.name').' | Specialist Care with a Personal Approach')</title>
    <meta name="description" content="@yield('meta_description', config('clinic.description'))">

    <meta property="og:title" content="@yield('title', config('clinic.name').' | Specialist Care with a Personal Approach')">
    <meta property="og:description" content="@yield('meta_description', config('clinic.description'))">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400..700&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="flex min-h-screen flex-col">
    <x-navbar />

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Global booking modal, available from every "Book an Appointment" CTA. --}}
    <x-appointment-modal />

    <x-footer />

    @stack('scripts')
</body>
</html>
