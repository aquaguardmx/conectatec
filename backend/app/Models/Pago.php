<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'idPago';

    protected $fillable = [
        'id_Usuario',
        'Metodo_Pago',
        'Nombre_Tarjeta',
        'Numero_Tarjeta',
        'Monto'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_Usuario', 'idUsuario');
    }
}
