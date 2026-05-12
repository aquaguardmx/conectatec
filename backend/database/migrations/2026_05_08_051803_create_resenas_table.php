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
        Schema::create('resenas', function (Blueprint $table) {
            $table->increments('idCalificacion');
            $table->foreignId('id_usuario')->references('idUsuario')->on('Usuario')->onDelete('cascade');
            $table->unsignedInteger('id_producto');
            $table->foreign('id_producto')->references('id_producto')->on('Producto')->onDelete('cascade');
            $table->integer('Puntuacion');
            $table->mediumText('Comentario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
