@extends('layouts.app')

@section('title', 'Kiosco')
@section('subtitulo', 'Presione una opción para obtener su turno')
@section('clase', 'ancho')

@section('content')
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 2rem; font-weight: bold; color: #0f2742; margin-bottom: 10px;">Seleccione el servicio que necesita</h2>
        <p style="color: #5b6877;">Presione una opción para obtener su turno</p>
    </div>

    <div class="llamados">
        @foreach($servicios as $prefijo => $nombre)
            <form method="POST" action="{{ route('kiosco.store') }}">
                @csrf
                <input type="hidden" name="servicio" value="{{ $prefijo }}">

                <button type="submit" class="btn-servicio">
                    <div class="circulo-servicio">{{ $prefijo }}</div>
                    <h3>{{ $nombre }}</h3>
                    <span>OBTENER TURNO</span>
                </button>
            </form>
        @endforeach
    </div>
@endsection
