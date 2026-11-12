<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kiosco de Turnos</title>
    <style>
        :root {
            --azul-oscuro: #0f2742;
            --azul: #1d4e89;
            --azul-claro: #e8eff7;
            --gris-texto: #5b6877;
            --gris-borde: #d5dce5;
            --fondo: #f4f6f9;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--fondo);
            color: var(--azul-oscuro);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: var(--azul-oscuro);
            color: #fff;
            padding: 24px 48px;
            border-bottom: 4px solid var(--azul);
        }

        header h1 {
            font-size: 1.6rem;
            font-weight: 600;
            letter-spacing: .5px;
        }

        header p {
            margin-top: 4px;
            font-size: .95rem;
            color: #b9c7d8;
        }

        main {
            flex: 1;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 48px 24px;
        }

        .titulo {
            text-align: center;
            margin-bottom: 36px;
        }

        .titulo h2 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .titulo p {
            margin-top: 8px;
            color: var(--gris-texto);
        }

        .servicios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .servicios form { display: contents; }

        .tarjeta {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 40px 24px;
            background: #fff;
            border: 1px solid var(--gris-borde);
            border-top: 4px solid var(--azul);
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            color: var(--azul-oscuro);
            box-shadow: 0 2px 6px rgba(15, 39, 66, .06);
            transition: transform .15s, box-shadow .15s, background .15s;
        }

        .tarjeta:hover,
        .tarjeta:focus-visible {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15, 39, 66, .15);
            background: var(--azul-claro);
            outline: none;
        }

        .tarjeta:active { transform: translateY(0); }

        .letra {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--azul-oscuro);
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nombre {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .accion {
            font-size: .85rem;
            color: var(--gris-texto);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: .85rem;
            color: var(--gris-texto);
            border-top: 1px solid var(--gris-borde);
            background: #fff;
        }
    </style>
</head>
<body>

    <header>
        <h1>Sistema de Turnos</h1>
        <p>Atención al cliente</p>
    </header>

    <main>
        <div class="titulo">
            <h2>Seleccione el servicio que necesita</h2>
            <p>Presione una opción para obtener su turno</p>
        </div>

        <div class="servicios">
            @foreach ($servicios as $prefijo => $nombre)
                <form method="POST" action="{{ route('kiosco.store') }}">
                    @csrf
                    <input type="hidden" name="servicio" value="{{ $prefijo }}">
                    <button type="submit" class="tarjeta">
                        <span class="letra">{{ $prefijo }}</span>
                        <span class="nombre">{{ $nombre }}</span>
                        <span class="accion">Obtener turno</span>
                    </button>
                </form>
            @endforeach
        </div>
    </main>

    <footer>
        Gracias por su preferencia
    </footer>

</body>
</html>
