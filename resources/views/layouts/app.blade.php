<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1"
    >
    <title>@yield('title', 'MONSWHEEL')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/monswheel.png') }}">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white min-h-screen overflow-x-hidden">

    <!-- APP WRAPPER -->
    <div class="min-h-screen flex flex-col">

        {{-- TOP BAR SLOT --}}
        @yield('topbar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
