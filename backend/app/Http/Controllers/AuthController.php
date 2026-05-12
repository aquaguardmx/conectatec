<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validamos que el frontend envíe todo correctamente
        $request->validate([
            'nombre' => 'required|string|max:100',
            'Matricula' => 'required|string|max:20|unique:Usuario,Matricula',
            'Correo_Institucional' => 'required|string|email|max:100|unique:Usuario,Correo_Institucional',
            'contrasena' => 'required|string|min:8',
            'Telefono' => 'nullable|string|max:15',
            'Rol' => 'required|string|max:45'
        ]);

        // 2. Creamos el usuario en la base de datos
        $usuario = User::create([
            'nombre' => $request->nombre,
            'Matricula' => $request->Matricula,
            'Correo_Institucional' => $request->Correo_Institucional,
            'contrasena' => Hash::make($request->contrasena), // ¡Súper importante encriptarla!
            'Telefono' => $request->Telefono,
            'Rol' => $request->Rol,
        ]);

        // 3. (Opcional pero útil) Creamos un token por si quieres iniciar sesión automáticamente
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // 4. Respondemos al frontend que todo salió bien
        return response()->json([
            'mensaje' => 'Usuario registrado exitosamente',
            'usuario' => $usuario,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Validamos que nos envíen email y password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Buscamos al usuario en tu tabla usando tu columna personalizada
        $usuario = User::where('Correo_Institucional', $request->email)->first();

        // 3. Verificamos si el usuario existe y si la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->contrasena)) {
            // Si falla, enviamos un error 401 (No Autorizado)
            return response()->json([
                'message' => 'Credenciales incorrectas. Verifica tu correo o contraseña.'
            ], 401);
        }

        // 4. Si todo es correcto, generamos un nuevo token de acceso
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // 5. Respondemos con los datos del usuario y su llave (token)
        return response()->json([
            'mensaje' => 'Inicio de sesión exitoso',
            'user' => $usuario,
            'token' => $token
        ], 200);
    }
}
