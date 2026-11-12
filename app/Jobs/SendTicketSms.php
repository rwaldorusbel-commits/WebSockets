<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTicketSms implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $ticketId, public string $type) {}

    public function handle(NotificationService $notifier): void
    {
        $t = Ticket::with('counter', 'service')->find($this->ticketId);

        if (! $t || ! $t->phone) {
            return;
        }

        $message = $this->type === 'called'
            ? "Turno {$t->code}: es su turno. Diríjase a {$t->counter?->name}."
            : "Turno {$t->code}: faltan 2 personas antes de usted. Vaya acercándose a la oficina.";

        $notifier->send($t->phone, $message);
    }
}
