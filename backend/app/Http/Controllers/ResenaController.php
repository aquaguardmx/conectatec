<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resena;
use App\Models\Pedido;

class ResenaController extends Controller
{
    public function getComprasPendientes(Request $request)
    {
        $id_usuario = $request->user()->idUsuario;

        // Obtener pedidos del usuario con sus productos
        $pedidos = Pedido::where('id_usuario', $id_usuario)
            ->with('producto')
            ->get();

        // Obtener productos únicos comprados
        $productosComprados = $pedidos->pluck('producto')->unique('id_producto')->filter();

        // Obtener reseñas hechas por el usuario
        $resenasHechas = Resena::where('id_usuario', $id_usuario)->get()->keyBy('id_producto');

        // Formatear resultado
        $resultado = $productosComprados->map(function ($producto) use ($resenasHechas) {
            $yaCalificado = $resenasHechas->has($producto->id_producto);
            return [
                'producto' => $producto,
                'ya_calificado' => $yaCalificado,
                'resena' => $yaCalificado ? $resenasHechas[$producto->id_producto] : null
            ];
        });

        return response()->json(array_values($resultado->toArray()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:Producto,id_producto',
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|min:20',
        ]);

        $id_usuario = $request->user()->idUsuario;

        // Verificar que el usuario compró el producto
        $haComprado = Pedido::where('id_usuario', $id_usuario)
            ->where('id_producto', $request->id_producto)
            ->exists();

        if (!$haComprado) {
            return response()->json(['message' => 'No puedes calificar un producto que no has comprado.'], 403);
        }

        // Verificar que no haya calificado ya
        $yaCalificado = Resena::where('id_usuario', $id_usuario)
            ->where('id_producto', $request->id_producto)
            ->exists();

        if ($yaCalificado) {
            return response()->json(['message' => 'Ya has calificado este producto.'], 400);
        }

        $resena = Resena::create([
            'id_usuario' => $id_usuario,
            'id_producto' => $request->id_producto,
            'Puntuacion' => $request->puntuacion,
            'Comentario' => $request->comentario,
        ]);

        return response()->json(['success' => true, 'data' => $resena]);
    }
}
