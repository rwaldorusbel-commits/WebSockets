@extends('layouts.app')

@section('title', 'Ventanillas')
@section('subtitulo', 'Administración de ventanillas')
@section('clase', 'ancho')

@section('content')

    {{-- Formulario para agregar --}}
    <div class="panel mb-6">
        <h2>Agregar Ventanilla</h2>

        <form method="POST" action="{{ route('admin.ventanillas.store') }}" class="flex items-end gap-4" style="flex-wrap: wrap;">
            @csrf

            <div class="campo" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label>Nombre de la ventanilla</label>
                <input type="text" name="name" required placeholder="Ej. Ventanilla 2">
            </div>

            <div class="campo" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label>Operador asignado</label>
                <select name="user_id">
                    <option value="">— Sin asignar —</option>
                    @foreach($operators as $op)
                        <option value="{{ $op->id }}">{{ $op->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn" style="height: 42px;">Agregar</button>
        </form>
    </div>

    {{-- Tabla de ventanillas existentes --}}
    <div class="panel">
        <h2>Ventanillas registradas</h2>

        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Ventanilla</th>
                    <th>Operador asignado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($counters as $counter)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.ventanillas.update', $counter) }}" id="form-update-{{ $counter->id }}">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $counter->name }}" required style="padding: 8px; width: 100%;">
                        </td>
                        <td>
                                <select name="user_id" style="padding: 8px; width: 100%;">
                                    <option value="">— Sin asignar —</option>
                                    @foreach($operators as $op)
                                        <option value="{{ $op->id }}" {{ $counter->user_id == $op->id ? 'selected' : '' }}>{{ $op->name }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <td style="white-space: nowrap;">
                                <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.9rem;">Guardar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.ventanillas.destroy', $counter) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar ventanilla?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-rojo" style="padding: 8px 16px; font-size: 0.9rem;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="vacio">No hay ventanillas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
