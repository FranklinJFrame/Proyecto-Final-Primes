<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Solicitar Devolución</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Asumiendo que usas Vite y app.css incluye Tailwind --}}
    {{-- Si no usas Vite, puedes enlazar tu CSS directamente aquí --}}
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    {{-- Estilos de Tailwind (si no están incluidos en app.css vía Vite) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    {{-- Livewire Styles (si planeas usar Livewire en esta página) --}}
    {{-- @livewireStyles --}}
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800 min-h-screen">
    {{-- Si tienes una barra de navegación común, podrías incluirla aquí --}}
    {{-- @include('layouts.navigation') --}}

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    {{-- @livewireScripts --}}
    @stack('modals')
    @stack('scripts')
</body>
</html> 