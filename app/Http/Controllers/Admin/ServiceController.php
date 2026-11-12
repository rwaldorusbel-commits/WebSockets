<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services', ['services' => Service::orderBy('prefix')->get()]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'   => 'required|string|max:100',
            'prefix' => 'required|alpha|max:3|unique:services,prefix',
        ]);

        $data['prefix'] = strtoupper($data['prefix']);
        Service::create($data + ['is_active' => true]);

        return back()->with('success', 'Servicio creado.');
    }

    // Activar / desactivar
    public function update(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(Service $service)
    {
        if ($service->tickets()->exists()) {
            return back()->with('error', 'Tiene turnos registrados. Mejor desactívalo.');
        }

        $service->delete();

        return back()->with('success', 'Servicio eliminado.');
    }
}
