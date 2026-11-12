@extends('layouts.app')

@section('title', 'Mi Turno')
@section('subtitulo', 'Su turno ha sido generado')
@section('clase', 'angosto')

@push('head')
<style>
    @page { size: 80mm auto; margin: 4mm; }
    @media print {
        body * { visibility: hidden; }
        #printable, #printable * { visibility: visible; }
        #printable { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
    <div id="printable" class="panel ticket">

        <p class="servicio" style="margin-bottom: 5px;">Su turno</p>

        {{-- Usamos $turno en lugar de $ticket --}}
        <div class="codigo">{{ $turno->codigo }}</div>

        <p style="font-size: 1.2rem; margin-bottom: 10px;">
            @if($turno->servicio === 'A') Atención general
            @elseif($turno->servicio === 'B') Caja
            @elseif($turno->servicio === 'C') Reclamos
            @else {{ $turno->servicio }}
            @endif
        </p>

        <p style="font-size: 0.85rem; color: #5b6877;">
            {{ $turno->created_at->format('d/m/Y H:i') }}
        </p>

        {{-- Código QR (opcional, requiere instalar una librería JS) --}}
        <div class="flex justify-center my-5" style="display: flex; justify-content: center; margin: 20px 0;">
            <canvas id="qr" data-url="{{ route('ticket.show', $turno) }}"></canvas>
        </div>
        <p style="font-size: 0.75rem; color: #5b6877;">Escanee el código para seguir su turno desde el celular</p>

        <div class="no-print" style="margin-top: 30px; border-top: 1px dashed #d5dce5; padding-top: 20px;">
            <p style="color: #5b6877; margin-bottom: 5px;">Personas delante de usted:</p>

            {{-- Aquí usamos la variable $posicion que calcula tu KioskController --}}
            <div id="pos" class="codigo" style="font-size: 3rem; margin: 10px 0;">{{ $posicion ?? '...' }}</div>

            <div id="status" class="badge estado-{{ $turno->estado }}" style="margin-top: 15px;">
                @if ($turno->estado === 'espera')
                    En espera
                @else
                    {{ $turno->estado }}
                @endif
            </div>

            <button onclick="window.print()" class="btn" style="margin-top: 25px; width: 100%;">
                Imprimir ticket
            </button>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;" class="no-print">
        <a href="{{ route('kiosco.index') }}" class="btn" style="background: transparent; color: #1d4e89; border: 1px solid #1d4e89;">
            Volver al inicio
        </a>
    </div>

    <script>
        // Pasamos el ID del turno para que el JS (display.js o ticket.js) lo use
        window.TICKET_ID = {{ $turno->id }};
    </script>

@endsection
