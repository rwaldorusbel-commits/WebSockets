<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        [$from, $to] = $this->range($r);

        $base = fn () => DB::table('tickets')->whereBetween('ticket_date', [$from, $to]);

        $total    = $base()->count();
        $byStatus = $base()->select('status', DB::raw('COUNT(*) as total'))
                           ->groupBy('status')->pluck('total', 'status');

        $done   = (int) ($byStatus['done'] ?? 0);
        $noShow = (int) ($byStatus['no_show'] ?? 0);

        $avgWait = $base()->whereNotNull('called_at')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, called_at)'));

        $avgService = $base()->whereNotNull('started_at')->whereNotNull('finished_at')
            ->where('status', 'done')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, started_at, finished_at)'));

        $byService = DB::table('tickets')
            ->join('services', 'services.id', '=', 'tickets.service_id')
            ->whereBetween('tickets.ticket_date', [$from, $to])
            ->groupBy('services.id', 'services.name')
            ->select(
                'services.name',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(tickets.status = 'no_show') as no_shows"),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, tickets.created_at, tickets.called_at)) as avg_wait'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, tickets.started_at, tickets.finished_at)) as avg_service')
            )->get();

        $byCounter = DB::table('tickets')
            ->join('counters', 'counters.id', '=', 'tickets.counter_id')
            ->whereBetween('tickets.ticket_date', [$from, $to])
            ->groupBy('counters.id', 'counters.name')
            ->select(
                'counters.name',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(tickets.status = 'done') as done"),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, tickets.started_at, tickets.finished_at)) as avg_service')
            )->get();

        $perDay = $base()->select('ticket_date', DB::raw('COUNT(*) as total'))
            ->groupBy('ticket_date')->orderBy('ticket_date')->pluck('total', 'ticket_date');

        $perHour = $base()->select(DB::raw('HOUR(created_at) as h'), DB::raw('COUNT(*) as total'))
            ->groupBy('h')->pluck('total', 'h');

        $chart = [
            'days'     => ['labels' => $perDay->keys(), 'values' => $perDay->values()],
            'hours'    => [
                'labels' => collect(range(0, 23))->map(fn ($h) => sprintf('%02d:00', $h)),
                'values' => collect(range(0, 23))->map(fn ($h) => (int) ($perHour[$h] ?? 0)),
            ],
            'services' => ['labels' => $byService->pluck('name'), 'values' => $byService->pluck('total')],
            'status'   => ['labels' => $byStatus->keys(), 'values' => $byStatus->values()],
        ];

        return view('admin.reports', [
            'from'       => $from,
            'to'         => $to,
            'total'      => $total,
            'done'       => $done,
            'noShowPct'  => $total ? round($noShow * 100 / $total, 1) : 0,
            'avgWait'    => $avgWait,
            'avgService' => $avgService,
            'byService'  => $byService,
            'byCounter'  => $byCounter,
            'chart'      => $chart,
        ]);
    }

    public function export(Request $r): StreamedResponse
    {
        [$from, $to] = $this->range($r);

        return response()->streamDownload(function () use ($from, $to) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel respete las tildes

            fputcsv($out, ['Fecha', 'Código', 'Servicio', 'Ventanilla', 'Estado', 'Preferencial',
                'Emitido', 'Llamado', 'Inicio', 'Fin', 'Espera (min)', 'Atención (min)']);

            Ticket::with('service', 'counter')
                ->whereBetween('ticket_date', [$from, $to])
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $t) {
                        fputcsv($out, [
                            $t->ticket_date->format('Y-m-d'),
                            $t->code,
                            $t->service?->name,
                            $t->counter?->name,
                            $t->status,
                            $t->priority ? 'Sí' : 'No',
                            $t->created_at?->format('H:i:s'),
                            $t->called_at?->format('H:i:s'),
                            $t->started_at?->format('H:i:s'),
                            $t->finished_at?->format('H:i:s'),
                            $t->called_at ? round(($t->called_at->timestamp - $t->created_at->timestamp) / 60, 1) : '',
                            ($t->started_at && $t->finished_at)
                                ? round(($t->finished_at->timestamp - $t->started_at->timestamp) / 60, 1) : '',
                        ]);
                    }
                });

            fclose($out);
        }, "turnos_{$from}_{$to}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function range(Request $r): array
    {
        $from = $r->date('from')?->toDateString() ?? today()->subDays(6)->toDateString();
        $to   = $r->date('to')?->toDateString() ?? today()->toDateString();

        return [$from, $to];
    }
}
