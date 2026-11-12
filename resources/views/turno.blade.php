@extends('layouts.app')

@section('title', 'Mi turno')
@section('subtitulo', 'Su turno ha sido generado')
@section('clase', 'angosto')

@section('content')
    <div class="panel ticket">

        {{-- Usamos la función del modelo para mostrar el nombre del servicio --}}
        <div class="servicio">{{ $turno->nombre_servicio }}</div>

        {{-- El código grande del turno --}}
        <div class="codigo">{{ $turno->codigo }}</div>

        {{-- La etiqueta de estado (Espera, Llamado, etc.) --}}
        <span id="estado" class="badge estado-{{ $turno->estado }}">
            {{ $turno->estado }}
        </span>

        {{-- Aquí se mostrará la posición en la fila --}}
        <div class="posicion" id="posicion">
            Calculando posición en la fila…
        </div>
    </div>

    <p class="vacio">Conserve este código. La página se actualiza sola.</p>

    <div style="text-align:center">
        <a href="{{ route('kiosco.index') }}" class="btn">Volver al inicio</a>
    </div>

    <script>
        // Diccionario de textos según el estado
        const textos = {
            espera: 'En espera',
            llamado: '¡Es su turno! Diríjase al puesto de atención',
            atendido: 'Atención finalizada',
            cancelado: 'Turno cancelado'
        };

        async function actualizar() {
            try {
                // Hacemos la petición a la ruta de posición
                const r = await fetch("{{ route('ticket.position', $turno) }}", {
                    headers: { 'Accept': 'application/json' }
                });
                const d = await r.json();

                // Actualizamos el badge de estado
                const estado = document.getElementById('estado');
                estado.className = 'badge estado-' + d.estado;
                estado.textContent = textos[d.estado] || d.estado;

                // Actualizamos la posición en la fila
                const pos = document.getElementById('posicion');
                if (d.posicion) {
                    pos.innerHTML = 'Su posición en la fila<strong>' + d.posicion + '</strong>';
                } else {
                    pos.innerHTML = textos[d.estado] || d.estado;
                }
            } catch (e) {
                console.error("Error al actualizar la posición:", e);
            }
        }

        // Ejecutamos al cargar la página
        actualizar();

        // Y luego cada 5 segundos
        setInterval(actualizar, 5000);
    </script>
@endsection
