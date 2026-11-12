<x-app-layout>
<div class="max-w-4xl mx-auto space-y-6">
    @include('partials.flash')
    <h1 class="text-2xl font-bold">Ventanillas</h1>

    <form method="POST" action="{{ route('admin.ventanillas.store') }}"
          class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
        @csrf
        <div>
            <label class="block text-sm">Nombre</label>
            <input name="name" required placeholder="Ventanilla 2" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm">Operador</label>
            <select name="user_id" class="border rounded px-3 py-2">
                <option value="">— Sin asignar —</option>
                @foreach ($operators as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Agregar</button>
    </form>

    <table class="w-full bg-white rounded shadow text-left">
        <thead class="bg-gray-50 text-sm">
            <tr><th class="p-3">Ventanilla</th><th>Operador asignado</th><th class="text-right pr-3">Acciones</th></tr>
        </thead>
        <tbody>
        @foreach ($counters as $c)
            <tr class="border-t">
                <td class="p-3 font-bold">{{ $c->name }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.ventanillas.update', $c) }}" class="flex gap-2">
                        @csrf @method('PUT')
                        <select name="user_id" class="border rounded px-2 py-1 text-sm">
                            <option value="">— Sin asignar —</option>
                            @foreach ($operators as $u)
                                <option value="{{ $u->id }}" @selected($c->user_id === $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <button class="text-indigo-600 text-sm hover:underline">Guardar</button>
                    </form>
                </td>
                <td class="text-right pr-3">
                    <form method="POST" action="{{ route('admin.ventanillas.destroy', $c) }}"
                          onsubmit="return confirm('¿Eliminar ventanilla?')">
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
