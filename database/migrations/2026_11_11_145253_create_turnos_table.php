<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('servicio');
            $table->string('codigo');          // ej: A-001
            $table->unsignedInteger('numero'); // consecutivo por servicio y día
            $table->enum('estado', ['espera', 'llamado', 'atendido', 'cancelado'])->default('espera');
            $table->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
