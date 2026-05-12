<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';
    protected $primaryKey = 'idCalificacion';
    
    protected $fillable = [
        'id_usuario',
        'id_producto',
        'Puntuacion',
        'Comentario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'idUsuario');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
