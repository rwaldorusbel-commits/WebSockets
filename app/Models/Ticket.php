<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
   protected $fillable = ['service_id','counter_id','ticket_date','number','code','status',
    'priority','document','phone','called_at','started_at','finished_at','notified_at'];

protected $casts = ['ticket_date' => 'date', 'called_at' => 'datetime',
    'started_at' => 'datetime', 'finished_at' => 'datetime', 'notified_at' => 'datetime'];

// Preferenciales primero, luego por orden de llegada
public function scopeOrdered($q)
{
    return $q->orderByDesc('priority')->orderBy('id');
}

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function scopeToday($q)
    {
        return $q->whereDate('ticket_date', today());
    }

    public function scopeWaiting($q)
    {
        return $q->where('status', 'waiting');
    }
}
