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
        Schema::create('Carrito', function (Blueprint $table) {
            $table->increments('idCarrito');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedInteger('id_producto')->nullable();
            $table->string('Seleccion', 45)->nullable();
            $table->integer('Cantidad')->default(1);

            // Llaves foráneas
            $table->foreign('id_usuario')->references('idUsuario')->on('Usuario')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('Producto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Carrito');
    }
};
