<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('servicio'); // 'A', 'B' o 'C'
            $table->integer('numero');  // 1, 2, 3...
            $table->string('codigo');   // 'A-001'
            $table->string('estado')->default('espera'); // espera, llamado, atendido, cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
