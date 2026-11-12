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
        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('service_id')->constrained();
            $t->foreignId('counter_id')->nullable()->constrained();
            $t->date('ticket_date');
            $t->unsignedInteger('number');
            $t->string('code', 10);                       // A-001
            $t->enum('status', ['waiting','called','attending','done','no_show','cancelled'])
              ->default('waiting');
            $t->string('document', 20)->nullable();       // DNI opcional
            $t->string('phone', 20)->nullable();
            $t->timestamp('called_at')->nullable();
            $t->timestamp('started_at')->nullable();
            $t->timestamp('finished_at')->nullable();
            $t->timestamps();

            $t->unique(['service_id', 'ticket_date', 'number']);
            $t->index(['status', 'service_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
