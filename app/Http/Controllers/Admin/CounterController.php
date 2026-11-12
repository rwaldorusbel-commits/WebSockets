<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        return view('admin.counters', [
            'counters'  => Counter::with('user')->orderBy('name')->get(),
            'operators' => User::where('role', 'operator')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'    => 'required|string|max:60',
            'user_id' => 'nullable|exists:users,id|unique:counters,user_id',
        ]);

        Counter::create($data + ['is_active' => true]);

        return back()->with('success', 'Ventanilla creada.');
    }

    // Asignar operador
    public function update(Request $r, Counter $counter)
    {
        $data = $r->validate([
            'user_id' => 'nullable|exists:users,id|unique:counters,user_id,'.$counter->id,
        ]);

        $counter->update($data);

        return back()->with('success', 'Operador asignado.');
    }

    public function destroy(Counter $counter)
    {
        if (Ticket::where('counter_id', $counter->id)->exists()) {
            return back()->with('error', 'Tiene turnos atendidos, no se puede eliminar.');
        }

        $counter->delete();

        return back()->with('success', 'Ventanilla eliminada.');
    }
}
