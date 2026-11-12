<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Turnos' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
    @auth
        <nav class="bg-white shadow px-6 py-3 flex justify-between items-center">
            <span class="font-bold">Sistema de Turnos</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-600 hover:underline">
                    Cerrar sesión ({{ auth()->user()->name }})
                </button>
            </form>
        </nav>
    @endauth

    <main class="{{ $wide ?? false ? 'p-6' : 'max-w-xl mx-auto p-6' }}">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
