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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('idPedidos');
            $table->unsignedBigInteger('NoPedidos');
            $table->string('Direccion', 255);
            $table->tinyInteger('Recibido')->default(0);
            $table->string('Ruta', 45)->nullable();
            $table->tinyInteger('Entregado')->default(0);
            $table->foreignId('id_usuario')->references('idUsuario')->on('Usuario')->onDelete('cascade');
            $table->unsignedInteger('id_producto');
            $table->foreign('id_producto')->references('id_producto')->on('Producto')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
