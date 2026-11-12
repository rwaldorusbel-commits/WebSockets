<x-app-layout>
<div class="max-w-4xl mx-auto space-y-6">
    @include('partials.flash')
    <h1 class="text-2xl font-bold">Usuarios</h1>

    <form method="POST" action="{{ route('admin.usuarios.store') }}"
          class="bg-white p-4 rounded shadow grid gap-3 md:grid-cols-5 items-end">
        @csrf
        <input name="name" required placeholder="Nombre" class="border rounded px-3 py-2">
        <input type="email" name="email" required placeholder="Correo" class="border rounded px-3 py-2">
        <input type="password" name="password" required minlength="8" placeholder="Contraseña" class="border rounded px-3 py-2">
        <select name="role" class="border rounded px-3 py-2">
            <option value="operator">Operador</option>
            <option value="admin">Administrador</option>
        </select>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Crear</button>
    </form>

    <table class="w-full bg-white rounded shadow text-left">
        <thead class="bg-gray-50 text-sm">
            <tr><th class="p-3">Nombre</th><th>Correo</th><th>Rol</th><th class="text-right pr-3"></th></tr>
        </thead>
        <tbody>
        @foreach ($users as $u)
            <tr class="border-t">
                <td class="p-3">{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role === 'admin' ? 'Administrador' : 'Operador' }}</td>
                <td class="text-right pr-3">
                    @if ($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.usuarios.destroy', $u) }}"
                              onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm hover:underline">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
