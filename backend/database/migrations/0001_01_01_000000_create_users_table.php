<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Usuario', function (Blueprint $table) {
            // idUsuario INT AUTO_INCREMENT PRIMARY KEY
            $table->id('idUsuario');

            $table->string('nombre', 100);
            $table->string('Matricula', 20)->unique();
            $table->string('Correo_Institucional', 100)->unique();
            $table->string('contrasena', 255);
            $table->string('Telefono', 15)->nullable();

            // Fecha_Registro DATETIME
            $table->datetime('Fecha_Registro')->useCurrent();

            $table->string('Rol', 45);

            // Campos de utilidad para Laravel (opcionales pero recomendados para seguridad)
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};
