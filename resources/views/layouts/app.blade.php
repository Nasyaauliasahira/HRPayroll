@php
// HRCore Layout Shell — Refined Professional
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HRCore' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-black: #1a1a1a;
            --color-white: #fff;
            --color-page-bg: #F7F5F2;
            --color-border: #E8E6E1;
            --color-muted: #999;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--color-page-bg); }
        .numeric { font-family: 'DM Mono', monospace; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex bg-[#F7F5F2]">
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <div class="flex flex-1 flex-col min-h-screen">
        <!-- Topbar -->
        @include('layouts.topbar')
        <main class="flex-1 flex">
            <div class="flex-1 p-6">@yield('content')</div>
            @hasSection('right')
            <aside class="hidden lg:block w-72 flex-shrink-0 bg-transparent p-5">@yield('right')</aside>
            @endif
        </main>
    </div>
</body>
</html>
