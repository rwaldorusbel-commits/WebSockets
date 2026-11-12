@extends('layouts.app')

@section('title', 'Acceso de operadores')
@section('subtitulo', 'Acceso de operadores')
@section('clase', 'angosto')

@section('content')
    <div class="panel">
        <h2>Iniciar sesión</h2>

        @if ($errors->any())
            <div class="alerta alerta-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width:100%">Ingresar</button>
        </form>
    </div>
@endsection
