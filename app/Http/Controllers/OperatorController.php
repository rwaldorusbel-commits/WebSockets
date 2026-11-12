<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Service;
use App\Models\Turno; // <-- Cambiado a Turno
use App\Services\QueueService;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function index()
    {
        $counter = Counter::where('user_id', Auth::id())->firstOrFail();

        // Buscamos el turno actual en la tabla 'turnos'
        // (Asumiendo que tu tabla 'turnos' tiene una columna 'counter_id' o 'operador_id')
        $current = Turno::where('counter_id', $counter->id)
            ->whereIn('estado', ['llamado', 'atendiendo'])
            ->first();

        // Buscamos los que están en espera
        $waiting = Turno::where('estado', 'espera')
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->get();

        return view('operator', compact('counter', 'current', 'waiting'));
    }

    public function next(QueueService $queue)
    {
        $counter = Counter::where('user_id', Auth::id())->firstOrFail();

        $hasActive = Turno::where('counter_id', $counter->id)
            ->whereIn('estado', ['llamado', 'atendiendo'])->exists();

        if ($hasActive) {
            return back()->with('error', 'Finaliza el turno actual antes de llamar al siguiente.');
        }

        // Como el QueueService está hecho para Ticket, haremos la llamada manual aquí:
        $ticket = Turno::where('estado', 'espera')
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->first();

        if ($ticket) {
            $ticket->update([
                'estado' => 'llamado',
                'counter_id' => $counter->id // Asignamos la ventanilla
            ]);

            return back()->with('success', "Llamaste al turno {$ticket->codigo}");
        }

        return back()->with('error', 'No hay turnos en espera.');
    }

    public function start(Turno $ticket)
    {
        $ticket->update(['estado' => 'atendiendo']);
        return back();
    }

    public function finish(Turno $ticket)
    {
        $ticket->update(['estado' => 'atendido']);
        return back();
    }

    public function noShow(Turno $ticket)
    {
        $ticket->update(['estado' => 'ausente']);
        return back();
    }

    public function waiting()
    {
        // Vista parcial para actualizar la lista
        $waiting = Turno::where('estado', 'espera')
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->get();

        return view('operator._waiting', compact('waiting'));
    }
}
