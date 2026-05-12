<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // 1. Le decimos que la tabla se llama Usuario
    protected $table = 'Usuario';

    // 2. Le decimos que la llave primaria es idUsuario
    protected $primaryKey = 'idUsuario';

    // 3. Mapeamos los campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'Matricula',
        'Correo_Institucional',
        'contrasena',
        'Telefono',
        'Fecha_Registro',
        'Rol',
    ];

    // 4. Laravel necesita saber cuál es la columna de la contraseña
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // 5. Desactivamos los timestamps automáticos (created_at/updated_at) 
    // ya que tú usas Fecha_Registro
    public $timestamps = false;
}
