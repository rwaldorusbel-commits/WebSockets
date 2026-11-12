<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KioskController extends Controller
{
    // Servicios disponibles: prefijo => nombre
    private const SERVICIOS = [
        'A' => 'Atención general',
        'B' => 'Caja',
        'C' => 'Reclamos',
    ];

    public function index()
    {
        // Enviamos el arreglo de servicios a la vista
        return view('kiosco.index', ['servicios' => self::SERVICIOS]);
    }

 public function store(Request $request)
{
    // Recibimos 'servicio' (que será la letra A, B o C que envíe la vista)
    $data = $request->validate([
        'servicio' => ['required', 'in:' . implode(',', array_keys(self::SERVICIOS))],
    ]);

    $prefijo = $data['servicio'];

    // Transacción + bloqueo para evitar números duplicados
    $turno = DB::transaction(function () use ($prefijo) {
        $ultimo = Turno::where('servicio', $prefijo)
            ->whereDate('created_at', today())
            ->lockForUpdate()
            ->max('numero');

        $numero = ($ultimo ?? 0) + 1;

        return Turno::create([
            'servicio' => $prefijo,
            'numero'   => $numero,
            'codigo'   => sprintf('%s-%03d', $prefijo, $numero),
            'estado'   => 'espera',
        ]);
    });

    return redirect()->route('ticket.show', $turno);
}

    public function show(Turno $ticket)
    {
        // Asegúrate de tener un archivo resources/views/turno.blade.php
        return view('turno', ['turno' => $ticket]);
    }

    public function position(Turno $ticket)
    {
        $posicion = Turno::where('servicio', $ticket->servicio)
            ->where('estado', 'espera')
            ->whereDate('created_at', today())
            ->where('id', '<', $ticket->id)
            ->count() + 1;

        return response()->json([
            'codigo'   => $ticket->codigo,
            'estado'   => $ticket->estado,
            'posicion' => $ticket->estado === 'espera' ? $posicion : null,
        ]);
    }
}
