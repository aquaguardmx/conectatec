<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;

class PedidoController extends Controller
{
    // Para el cliente: sus propios pedidos
    public function index(Request $request)
    {
        $id_usuario = $request->user()->idUsuario;

        $pedidos = Pedido::where('id_usuario', $id_usuario)
            ->with('producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }

    // Para el vendedor: pedidos de sus productos
    public function indexVendedor(Request $request)
    {
        $id_vendedor = $request->user()->idUsuario;

        // Obtenemos los IDs de los productos del vendedor
        $productosIds = Producto::where('id_vendedor', $id_vendedor)
            ->pluck('id_producto');

        $pedidos = Pedido::whereIn('id_producto', $productosIds)
            ->with(['producto', 'usuario'])
            ->orderBy('fecha_entrega', 'asc')
            ->orderBy('hora_entrega', 'asc')
            ->get()
            ->map(function ($pedido) {
                return [
                    'idPedidos'      => $pedido->idPedidos,
                    'NoPedidos'      => $pedido->NoPedidos,
                    'punto_entrega'  => $pedido->punto_entrega,
                    'fecha_entrega'  => $pedido->fecha_entrega,
                    'hora_entrega'   => $pedido->hora_entrega,
                    'Entregado'      => $pedido->Entregado,
                    'created_at'     => $pedido->created_at,
                    'producto'       => [
                        'id_producto' => $pedido->producto?->id_producto,
                        'Nombre'      => $pedido->producto?->Nombre,
                        'Imagenes'    => $pedido->producto?->Imagenes,
                        'Precio'      => $pedido->producto?->Precio,
                    ],
                    'comprador' => [
                        'nombre' => $pedido->usuario?->nombre ?? 'Comprador',
                        'email'  => $pedido->usuario?->Correo_Institucional ?? '',
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }

    // Marcar un pedido como entregado
    public function marcarEntregado(Request $request, $id)
    {
        $id_vendedor = $request->user()->idUsuario;

        $productosIds = Producto::where('id_vendedor', $id_vendedor)
            ->pluck('id_producto');

        $pedido = Pedido::whereIn('id_producto', $productosIds)
            ->where('idPedidos', $id)
            ->firstOrFail();

        $pedido->Entregado = 1;
        $pedido->save();

        return response()->json(['success' => true, 'message' => 'Pedido marcado como entregado']);
    }
}
