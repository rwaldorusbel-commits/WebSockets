<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pantalla de turnos</title>
    @vite(['resources/css/app.css', 'resources/js/display.js'])
</head>
<body class="bg-slate-900 text-white min-h-screen">
<div class="grid md:grid-cols-3 min-h-screen">

    <section class="md:col-span-2 flex flex-col items-center justify-center p-10 text-center">
        <p class="text-3xl text-slate-300 mb-4">Turno actual</p>
        <div id="current" class="text-[9rem] leading-none font-extrabold">{{ $calls->first()?->code ?? '---' }}</div>
        <div id="counter" class="mt-6 text-5xl text-yellow-300">{{ $calls->first()?->counter?->name }}</div>
    </section>

    <aside class="bg-slate-800 p-8">
        <h2 class="text-2xl mb-4 text-slate-300">Últimos llamados</h2>
        <ul id="history" class="space-y-3 text-3xl">
            @foreach ($calls->skip(1) as $c)
                <li class="flex justify-between border-b border-slate-700 pb-2">
                    <span class="font-bold">{{ $c->code }}</span>
                    <span>{{ $c->counter?->name }}</span>
                </li>
            @endforeach
        </ul>

        <button id="enable" class="mt-10 px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700">
            Activar sonido y voz
        </button>
        <p id="conn" class="mt-4 text-sm text-slate-400">Conectando…</p>
    </aside>
</div>
</body>
</html>
