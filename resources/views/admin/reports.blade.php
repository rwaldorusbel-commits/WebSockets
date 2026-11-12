@extends('layouts.app')

@section('title', 'Reportes')
@section('subtitulo', 'Reportes y estadísticas')
@section('clase', 'ancho')

@section('content')
@php $min = fn ($s) => $s ? round($s / 60, 1).' min' : '—'; @endphp

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">

    {{-- Filtros --}}
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; margin-bottom: 20px; gap: 15px;">
        <h1 style="font-size: 1.5rem; font-weight: bold; margin: 0;">Reportes y estadísticas</h1>

        <form method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
            <div>
                <label style="display: block; font-size: 0.8rem;">Desde</label>
                <input type="date" name="from" value="{{ $from }}" style="border: 1px solid #ccc; padding: 5px; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem;">Hasta</label>
                <input type="date" name="to" value="{{ $to }}" style="border: 1px solid #ccc; padding: 5px; border-radius: 4px;">
            </div>
            <button type="submit" class="btn">Filtrar</button>
            <a href="{{ route('admin.reports.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-verde" style="text-decoration: none;">Exportar CSV</a>
        </form>
    </div>

    {{-- Tarjetas de resumen --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
        <div class="panel" style="padding: 15px;">
            <p style="font-size: 0.8rem; color: #666; margin: 0;">Turnos emitidos</p>
            <p style="font-size: 2rem; font-weight: bold; margin: 5px 0 0 0;">{{ $total }}</p>
        </div>
        <div class="panel" style="padding: 15px;">
            <p style="font-size: 0.8rem; color: #666; margin: 0;">Atendidos</p>
            <p style="font-size: 2rem; font-weight: bold; margin: 5px 0 0 0;">{{ $done }}</p>
        </div>
        <div class="panel" style="padding: 15px;">
            <p style="font-size: 0.8rem; color: #666; margin: 0;">Espera promedio</p>
            <p style="font-size: 2rem; font-weight: bold; margin: 5px 0 0 0;">{{ $min($avgWait) }}</p>
        </div>
        <div class="panel" style="padding: 15px;">
            <p style="font-size: 0.8rem; color: #666; margin: 0;">Atención promedio</p>
            <p style="font-size: 2rem; font-weight: bold; margin: 5px 0 0 0;">{{ $min($avgService) }}</p>
        </div>
    </div>

    <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Ausentismo: <strong>{{ $noShowPct }}%</strong> de los turnos.</p>

    {{-- Tablas de detalle --}}
    <div class="panel" style="overflow-x: auto; margin-bottom: 20px;">
        <h3 style="padding: 15px; margin: 0; border-bottom: 1px solid #eee;">Detalle por servicio</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f9f9f9; font-size: 0.9rem;">
                <tr>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Servicio</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Turnos</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Ausentes</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Espera prom.</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Atención prom.</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($byService as $s)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $s->name }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $s->total }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $s->no_shows }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $min($s->avg_wait) }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $min($s->avg_service) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel" style="overflow-x: auto; margin-bottom: 20px;">
        <h3 style="padding: 15px; margin: 0; border-bottom: 1px solid #eee;">Rendimiento por ventanilla</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f9f9f9; font-size: 0.9rem;">
                <tr>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Ventanilla</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Llamados</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Atendidos</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">Atención prom.</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($byCounter as $c)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $c->name }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $c->total }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $c->done }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $min($c->avg_service) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Dejamos los datos del gráfico por si luego quieres usarlos --}}
<script id="report-data" type="application/json">@json($chart)</script>

@endsection
