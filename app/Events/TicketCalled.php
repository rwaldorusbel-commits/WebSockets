<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCalled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Ticket $ticket) {
        $this->ticket->load('counter','service');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('queue'),                          // pantalla pública
            new Channel('ticket.'.$this->ticket->id),      // el ciudadano
        ];
    }

    public function broadcastAs(): string { return 'ticket.called'; }

    public function broadcastWith(): array
    {
        return [
            'id'      => $this->ticket->id,
            'code'    => $this->ticket->code,
            'counter' => $this->ticket->counter->name,
            'service' => $this->ticket->service->name,
        ];
    }
}
