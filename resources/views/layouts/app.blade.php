<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MONSWHEEL')</title>  
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/monswheel.png') }}">
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950">
    @yield('content')
</body>
</html>
