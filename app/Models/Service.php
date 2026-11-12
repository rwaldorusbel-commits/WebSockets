<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // Agrega esta línea con los campos de tu tabla
    protected $fillable = [
        'name',
        'prefix', // o 'code', dependiendo de cómo se llame la columna en tu BD
    ];
}
