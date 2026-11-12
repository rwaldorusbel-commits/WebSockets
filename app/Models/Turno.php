<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    // Campos que se pueden guardar masivamente
    protected $fillable = [
        'servicio',
        'codigo',
        'numero',
        'estado',
        'operador_id'
    ];

    // Lista de servicios disponibles
    public const SERVICIOS = [
        'A' => 'Atención general',
        'B' => 'Caja',
        'C' => 'Reclamos',
    ];

    // Función para obtener el nombre del servicio a partir de la letra
    public function getNombreServicioAttribute(): string
    {
        return self::SERVICIOS[$this->servicio] ?? $this->servicio;
    }
}
