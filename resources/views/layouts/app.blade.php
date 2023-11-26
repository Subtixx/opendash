<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @stack('styles')

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
@include("layouts.toast")
<div class="min-h-screen bg-base-300 text-base-content">
    <div class="mb-6">
        @include('layouts.navigation')
    </div>

    <main class="mx-auto sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-4 bg-base-100 text-base-content">
        <aside>
            <p>Copyright &copy; {{ date('Y')}} IT-Hock. All rights reserved.</p>
        </aside>
    </footer>
</div>
@stack('scripts')
@livewireScriptConfig
</body>
</html>
