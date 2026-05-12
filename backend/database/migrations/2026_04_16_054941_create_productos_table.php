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
        // En Laravel la convención es nombrar las tablas en plural y minúsculas ('productos')
        // Si necesitas estrictamente que se llame 'Producto' por base de datos legacy, cámbialo aquí.
        Schema::create('productos', function (Blueprint $table) {

            // id_producto INT AUTO_INCREMENT PRIMARY KEY
            $table->increments('id_producto');

            // id_vendedor INT
            $table->integer('id_vendedor');

            // Nombre VARCHAR(100)
            $table->string('Nombre', 100);

            // Descripcion MEDIUMTEXT
            $table->mediumText('Descripcion');

            // Precio DECIMAL(10,2)
            $table->decimal('Precio', 10, 2);

            // Stock INT
            $table->integer('Stock');

            // Imagenes VARCHAR(255)
            // (Agregué ->nullable() porque a veces al crear un producto aún no hay imágenes)
            $table->string('Imagenes', 255)->nullable();

            // Aceptado TINYINT
            // (Agregué ->default(0) asumiendo que un producto nuevo requiere aprobación por defecto)
            $table->tinyInteger('Aceptado')->default(0);

            // --- EXTRAS RECOMENDADOS ---

            // 1. Esto creará automáticamente las columnas 'created_at' y 'updated_at'
            $table->timestamps();

            // 2. Si 'id_vendedor' viene de una tabla 'vendedores', descomenta esta línea para crear la llave foránea
            // $table->foreign('id_vendedor')->references('id_vendedor')->on('vendedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
