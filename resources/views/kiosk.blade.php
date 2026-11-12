<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kiosk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-lg shadow p-8 w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-6">Kiosk de turnos</h1>

        <form method="POST" action="{{ route('kiosk.store') }}">
            @csrf

            <div class="space-y-2 mb-6 text-left">
                <input type="text" name="phone" placeholder="Celular (opcional, recibirá aviso por SMS)"
                       class="w-full border rounded px-3 py-2" maxlength="20"
                       value="{{ old('phone') }}">

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="priority" value="1">
                    Atención preferencial (adulto mayor, gestante, persona con discapacidad)
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white rounded px-4 py-3 font-semibold hover:bg-blue-700">
                Generar turno
            </button>
        </form>
    </div>

</body>
</html>
