<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'name',
        'user_id', // Asegúrate de tener este campo en tu tabla
    ];

    // AGREGA ESTA FUNCIÓN:
    public function user()
    {
        // Una ventanilla pertenece a un usuario (operador)
        return $this->belongsTo(User::class);
    }
}
