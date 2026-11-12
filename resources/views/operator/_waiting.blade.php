<div class="panel">
    <h2>Turnos en espera</h2>

    @if($waiting->isEmpty())
        <p class="vacio">No hay turnos en espera en este momento.</p>
    @else
        <ul class="lista-espera">
            @foreach($waiting as $ticket)
                <li>
                    <span class="font-bold">{{ $ticket->codigo }}</span>
                    <span>{{ $ticket->nombre_servicio }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
