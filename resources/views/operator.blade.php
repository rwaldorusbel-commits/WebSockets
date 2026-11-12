@extends('layouts.app')

@section('title', 'Panel del Operador')
@section('subtitulo', 'Mi ventanilla: ' . $counter->name)
@section('clase', 'ancho')

@section('content')

    {{-- Mensajes de éxito o error --}}
    @if (session('success'))
        <div class="alerta alerta-info" style="background: #dcf3e6; color: #1e7e4f; border-color: #bce3cd;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alerta alerta-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="pantalla">
        {{-- COLUMNA IZQUIERDA: Turno actual --}}
        <div class="panel actual">
            <h3 style="font-size: 1.2rem; margin-bottom: 20px;">Turno en atención</h3>

            @if ($current)
                <div class="text-center">
                    {{-- Variables corregidas a español --}}
                    <div class="codigo">{{ $current->codigo }}</div>

                    <div style="font-size: 1.2rem; color: #5b6877; margin-bottom: 10px;">
                        Servicio: {{ $current->servicio }}
                    </div>

                    <span class="badge {{ $current->estado === 'llamado' ? 'estado-espera' : 'estado-atendido' }}">
                        {{ $current->estado === 'llamado' ? 'Llamado, esperando al ciudadano' : 'En atención' }}
                    </span>
                </div>

                <div class="acciones" style="margin-top: 30px;">
                    @if ($current->estado === 'llamado')
                        <form method="POST" action="{{ url('operador/turno/'.$current->id.'/iniciar') }}">
                            @csrf
                            <button class="btn">Iniciar atención</button>
                        </form>

                        <form method="POST" action="{{ url('operador/turno/'.$current->id.'/ausente') }}"
                              onsubmit="return confirm('¿Marcar el turno como ausente?')">
                            @csrf
                            <button class="btn" style="background: #5b6877;">No se presentó</button>
                        </form>
                    @else
                        <form method="POST" action="{{ url('operador/turno/'.$current->id.'/finalizar') }}">
                            @csrf
                            <button class="btn btn-verde">Finalizar atención</button>
                        </form>
                    @endif
                </div>
            @else
                <div class="text-center" style="padding: 40px 0;">
                    <p style="color: #5b6877; margin-bottom: 20px;">No tienes ningún turno en curso.</p>

                    <form method="POST" action="{{ url('operador/llamar') }}">
                        @csrf
                        <button class="btn btn-grande">Llamar siguiente</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- COLUMNA DERECHA: Cola de espera --}}
        <div class="panel">
            <h3 style="font-size: 1.2rem; margin-bottom: 20px;">
                En espera (<span id="waiting-count">{{ $waiting->count() }}</span>)
            </h3>

            <div id="waiting-list">
                @include('operator._waiting')
            </div>
        </div>
    </div>

@endsection
