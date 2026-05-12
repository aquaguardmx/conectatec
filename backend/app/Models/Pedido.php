<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'idPedidos';

    protected $fillable = [
        'NoPedidos',
        'Direccion',
        'punto_entrega',
        'fecha_entrega',
        'hora_entrega',
        'Recibido',
        'Ruta',
        'Entregado',
        'id_usuario',
        'id_producto'
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
