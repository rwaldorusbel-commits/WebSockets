<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Turnos')</title>
    <!-- Cargar el CSS Corporativo -->
    <link rel="stylesheet" href="{{ asset('css/corporativo.css') }}">
    @stack('head')
</head>
<body>

    {{-- ENCABEZADO PRINCIPAL --}}
    <header class="encabezado">
        <div>
            <h1>Sistema de Turnos</h1>
            <p>@yield('subtitulo', 'Atención al cliente')</p>
        </div>
        @yield('acciones')
    </header>

    {{-- BARRA DE NAVEGACIÓN --}}
    <nav style="background: white; padding: 10px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
        @auth
            <div>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.reports') }}" style="margin-right: 15px;">Reportes</a>
                    <a href="{{ route('admin.servicios.index') }}" style="margin-right: 15px;">Servicios</a>
                    <a href="{{ route('admin.ventanillas.index') }}" style="margin-right: 15px;">Ventanillas</a>
                    <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
                @else
                    <a href="{{ url('operador') }}">Mi ventanilla</a>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">
                    Cerrar sesión ({{ auth()->user()->name }})
                </button>
            </form>
        @endauth
        @guest
            <a href="{{ route('login') }}">Iniciar sesión</a>
        @endguest
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="contenedor @yield('clase')">
        @yield('content')
    </main>

    {{-- PIE DE PÁGINA --}}
    <footer>
        Gracias por su preferencia
    </footer>

</body>
</html>
