<x-app-layout>
<div class="max-w-4xl mx-auto space-y-6">
    @include('partials.flash')
    <h1 class="text-2xl font-bold">Servicios</h1>

    <form method="POST" action="{{ route('admin.servicios.store') }}"
          class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
        @csrf
        <div>
            <label class="block text-sm">Nombre</label>
            <input name="name" required class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm">Prefijo</label>
            <input name="prefix" required maxlength="3" class="border rounded px-3 py-2 w-24 uppercase">
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Agregar</button>
    </form>

    <table class="w-full bg-white rounded shadow text-left">
        <thead class="bg-gray-50 text-sm">
            <tr><th class="p-3">Prefijo</th><th>Nombre</th><th>Estado</th><th class="text-right pr-3">Acciones</th></tr>
        </thead>
        <tbody>
        @foreach ($services as $s)
            <tr class="border-t">
                <td class="p-3 font-bold">{{ $s->prefix }}</td>
                <td>{{ $s->name }}</td>
                <td>
                    <span class="px-2 py-1 rounded text-xs {{ $s->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                        {{ $s->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="text-right pr-3 space-x-3">
                    <form method="POST" action="{{ route('admin.servicios.update', $s) }}" class="inline">
                        @csrf @method('PUT')
                        <button class="text-indigo-600 text-sm hover:underline">{{ $s->is_active ? 'Desactivar' : 'Activar' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.servicios.destroy', $s) }}" class="inline"
                          onsubmit="return confirm('¿Eliminar servicio?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-sm hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
