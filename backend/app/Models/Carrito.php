<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'Carrito'; // Nombre exacto de la tabla
    protected $primaryKey = 'idCarrito'; // Llave primaria personalizada

    // Desactivamos timestamps porque no se incluyeron en el SQL
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_producto',
        'Seleccion',
        'Cantidad'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'idUsuario');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
