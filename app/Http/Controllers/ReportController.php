<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fechas del filtro
        $from = $request->input('from', now()->subDays(6)->toDateString());
        $to = $request->input('to', now()->toDateString());

        // 2. Consulta base
        $query = Turno::whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);

        // 3. Tarjetas de resumen
        $total = $query->count();
        $done = (clone $query)->where('estado', 'atendido')->count();
        $noShow = (clone $query)->where('estado', 'ausente')->count();

        // Porcentaje de ausentismo
        $noShowPct = $total > 0 ? round(($noShow / $total) * 100, 1) : 0;

        // 4. Detalle por servicio (Agrupado por la letra A, B o C)
        $byService = (clone $query)->select(
            'servicio as name',
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(estado = 'ausente') as no_shows"),
            DB::raw('NULL as avg_wait'),    // Como no guardas tiempos de espera, lo dejamos en null
            DB::raw('NULL as avg_service')  // Como no guardas tiempos de atención, lo dejamos en null
        )->groupBy('servicio')->get();

        // 5. Detalle por ventanilla (Si no tienes operador_id en turnos, esto saldrá vacío)
        $byCounter = [];

        // 6. Datos para las gráficas (Opcional, las gráficas no se ven porque quitamos el JS)
        $chart = [
            'days' => [],
            'hours' => [],
            'services' => [],
            'status' => []
        ];

        return view('admin.reports', [
            'from' => $from,
            'to' => $to,
            'total' => $total,
            'done' => $done,
            'noShowPct' => $noShowPct,
            'avgWait' => null,
            'avgService' => null,
            'byService' => $byService,
            'byCounter' => $byCounter,
            'chart' => $chart
        ]);
    }

    // Método para exportar a CSV (opcional, por si le das clic al botón verde)
    public function export(Request $request)
    {
        // Aquí puedes agregar la lógica para descargar el CSV
        return back();
    }
}
