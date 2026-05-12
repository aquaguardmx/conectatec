<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{

    public function verifyCode(Request $request)
    {
        if ($request->code == '123456') {
            return response()->json(['message' => 'OK'], 200);
        }
        return response()->json(['message' => 'Error'], 401);
    }
    /**
     * PASO 1: Verificar si el correo existe
     */
    public function sendCode(Request $request)
    {
        // Validamos contra tu tabla 'Usuario' y columna 'Correo_Institucional'
        $request->validate([
            'email' => 'required|email|exists:Usuario,Correo_Institucional'
        ], [
            'email.exists' => 'El correo institucional ingresado no está registrado.'
        ]);

        // Si llega aquí, es que sí existe. 
        // Como no usas tabla de tokens, solo respondemos éxito.
        return response()->json([
            'message' => 'Correo verificado. Procede a ingresar el código.',
            'test_code' => '123456' // Código fijo para tus pruebas
        ], 200);
    }

    /**
     * PASO 3: Actualizar la contraseña final
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:Usuario,Correo_Institucional',
            'code' => 'required',
            'password' => 'required|min:8'
        ]);

        // Validación simple del código fijo
        if ($request->code !== '123456') {
            return response()->json(['message' => 'Código incorrecto.'], 401);
        }

        $usuario = User::where('Correo_Institucional', $request->email)->first();

        // Actualizamos la columna 'contrasena'
        $usuario->contrasena = Hash::make($request->password);
        $usuario->save();

        return response()->json(['message' => 'Contraseña actualizada con éxito.'], 200);
    }
}
