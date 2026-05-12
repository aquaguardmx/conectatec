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
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('idPago');
            $table->foreignId('id_Usuario')->references('idUsuario')->on('Usuario')->onDelete('cascade');
            $table->string('Metodo_Pago', 30);
            $table->string('Nombre_Tarjeta', 100)->nullable();
            $table->string('Numero_Tarjeta', 16)->nullable();
            $table->decimal('Monto', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
