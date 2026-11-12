<?php

namespace App\Services;

use App\Events\TicketCalled;
use App\Events\TicketCreated;
use App\Jobs\SendTicketSms;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class QueueService
{
    public function issue(Service $service, ?string $document = null, ?string $phone = null, bool $priority = false): Ticket
    {
        $ticket = DB::transaction(function () use ($service, $document, $phone, $priority) {
            Service::whereKey($service->id)->lockForUpdate()->first();

            $next = (Ticket::where('service_id', $service->id)->today()->max('number') ?? 0) + 1;

            return Ticket::create([
                'service_id'  => $service->id,
                'ticket_date' => today(),
                'number'      => $next,
                'code'        => sprintf('%s-%03d', $service->prefix, $next),
                'priority'    => $priority ? 1 : 0,
                'document'    => $document,
                'phone'       => $phone,
            ]);
        });

        TicketCreated::dispatch($ticket);

        return $ticket;
    }

    public function callNext(Counter $counter, array $serviceIds): ?Ticket
    {
        $ticket = DB::transaction(function () use ($counter, $serviceIds) {
            $ticket = Ticket::today()->waiting()
                ->whereIn('service_id', $serviceIds)
                ->ordered()
                ->lock('for update skip locked')
                ->first();

            if (! $ticket) {
                return null;
            }

            $ticket->update([
                'status'     => 'called',
                'counter_id' => $counter->id,
                'called_at'  => now(),
            ]);

            return $ticket;
        });

        if ($ticket) {
            TicketCalled::dispatch($ticket);

            if ($ticket->phone) {
                SendTicketSms::dispatch($ticket->id, 'called');
            }

            $this->notifyUpcoming($ticket->service_id);
        }

        return $ticket;
    }

    /** Avisa al ciudadano que está 3.º en la cola (faltan 2 delante). */
    public function notifyUpcoming(int $serviceId): void
    {
        $next = Ticket::today()->waiting()
            ->where('service_id', $serviceId)
            ->ordered()
            ->offset(2)
            ->first();

        if ($next && $next->phone && ! $next->notified_at) {
            $next->update(['notified_at' => now()]);
            SendTicketSms::dispatch($next->id, 'near');
        }
    }

    public function start(Ticket $t): void  { $t->update(['status' => 'attending', 'started_at' => now()]); }
    public function finish(Ticket $t): void { $t->update(['status' => 'done', 'finished_at' => now()]); }
    public function noShow(Ticket $t): void { $t->update(['status' => 'no_show', 'finished_at' => now()]); }

    public function position(Ticket $t): int
    {
        if ($t->status !== 'waiting') {
            return 0;
        }

        return Ticket::today()->waiting()
            ->where('service_id', $t->service_id)
            ->where(function ($q) use ($t) {
                $q->where('priority', '>', $t->priority)
                  ->orWhere(fn ($q2) => $q2->where('priority', $t->priority)->where('id', '<', $t->id));
            })
            ->count();
    }
}
