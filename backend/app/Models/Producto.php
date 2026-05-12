<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'Producto'; // Nombre exacto de la tabla
    protected $primaryKey = 'id_producto'; // Llave primaria personalizada

    // Desactiva timestamps si tu tabla no tiene created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_vendedor',
        'Nombre',
        'Descripcion',
        'categoria',
        'estado',
        'Precio',
        'Stock',
        'Imagenes',
        'Aceptado'
    ];

    protected $casts = [
        'Precio' => 'decimal:2',
        'Stock' => 'integer',
        'Aceptado' => 'boolean',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'id_vendedor', 'idUsuario');
    }
}
