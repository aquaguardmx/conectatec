<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('punto_entrega', 100)->default('Por definir')->after('Direccion');
            $table->date('fecha_entrega')->nullable()->after('punto_entrega');
            $table->time('hora_entrega')->nullable()->after('fecha_entrega');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['punto_entrega', 'fecha_entrega', 'hora_entrega']);
        });
    }
};
