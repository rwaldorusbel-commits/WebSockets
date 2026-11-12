<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $t) {
            $t->unsignedTinyInteger('priority')->default(0)->after('status'); // 0 normal, 1 preferencial
            $t->timestamp('notified_at')->nullable()->after('finished_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $t) {
            $t->dropColumn(['priority', 'notified_at']);
        });
    }
};
