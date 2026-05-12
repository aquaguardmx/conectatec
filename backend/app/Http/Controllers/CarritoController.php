<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Pago;
use App\Models\Pedido;

class CarritoController extends Controller
{
    // Obtener los productos del carrito del usuario logueado
    public function index(Request $request)
    {
        $id_usuario = $request->user()->idUsuario;

        $carrito = Carrito::where('id_usuario', $id_usuario)
                          ->with('producto') // Traer información del producto
                          ->get();

        return response()->json($carrito);
    }

    // Agregar un producto al carrito
    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|integer|exists:Producto,id_producto',
            'Seleccion' => 'nullable|string|max:45',
            'Cantidad' => 'nullable|integer|min:1'
        ]);

        $id_usuario = $request->user()->idUsuario;
        $id_producto = $request->id_producto;
        $seleccion = $request->Seleccion;
        $cantidad = $request->Cantidad ?? 1;

        // Verificar si ya existe en el carrito
        $item = Carrito::where('id_usuario', $id_usuario)
                       ->where('id_producto', $id_producto)
                       ->where('Seleccion', $seleccion)
                       ->first();

        if ($item) {
            // Si ya existe, solo sumar la cantidad
            $item->Cantidad += $cantidad;
            $item->save();
        } else {
            // Si no existe, crear nuevo registro
            $item = Carrito::create([
                'id_usuario' => $id_usuario,
                'id_producto' => $id_producto,
                'Seleccion' => $seleccion,
                'Cantidad' => $cantidad
            ]);
        }

        return response()->json([
            'message' => 'Producto agregado al carrito exitosamente',
            'item' => $item
        ], 201);
    }

    // Actualizar cantidad de un producto en el carrito
    public function update(Request $request, $idCarrito)
    {
        $request->validate([
            'Cantidad' => 'required|integer|min:1'
        ]);

        $id_usuario = $request->user()->idUsuario;
        
        $item = Carrito::where('id_usuario', $id_usuario)
                       ->where('idCarrito', $idCarrito)
                       ->firstOrFail();

        $item->Cantidad = $request->Cantidad;
        $item->save();

        return response()->json([
            'message' => 'Cantidad actualizada',
            'item' => $item
        ]);
    }

    // Eliminar del carrito
    public function destroy(Request $request, $idCarrito)
    {
        $id_usuario = $request->user()->idUsuario;
        
        $item = Carrito::where('id_usuario', $id_usuario)
                       ->where('idCarrito', $idCarrito)
                       ->firstOrFail();

        $item->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito'
        ]);
    }

    // Checkout: Realizar compra
    public function checkout(Request $request)
    {
        $request->validate([
            'metodo_pago'    => 'required|string|in:efectivo,tarjeta',
            'nombre_tarjeta' => 'nullable|string|max:100',
            'numero_tarjeta' => 'nullable|digits:16',
            'punto_entrega'  => 'required|string|max:100',
            'fecha_entrega'  => 'required|date|after:today',
            'hora_entrega'   => 'required|string|max:10',
        ]);

        $id_usuario = $request->user()->idUsuario;
        $carrito = Carrito::where('id_usuario', $id_usuario)->with('producto')->get();

        if ($carrito->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío'], 400);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $montoTotal = 0;
            foreach ($carrito as $item) {
                if ($item->producto) {
                    $montoTotal += ($item->producto->Precio * $item->Cantidad);
                }
            }

            $noPedidos = time(); // Generamos un id de pedido basado en el tiempo

            $pago = Pago::create([
                'id_Usuario' => $id_usuario,
                'Metodo_Pago' => $request->metodo_pago,
                'Nombre_Tarjeta' => $request->nombre_tarjeta,
                'Numero_Tarjeta' => $request->numero_tarjeta,
                'Monto' => $montoTotal
            ]);

            foreach ($carrito as $item) {
                $producto = $item->producto;
                
                if (!$producto) {
                    throw new \Exception("Producto no encontrado para el ítem con ID " . $item->id_producto);
                }

                if ($producto->Stock < $item->Cantidad) {
                    throw new \Exception("Stock insuficiente para el producto: " . $producto->Nombre);
                }

                // Descontar stock
                $producto->Stock -= $item->Cantidad;
                $producto->save();

                // Crear Pedido
                Pedido::create([
                    'NoPedidos'      => $noPedidos,
                    'Direccion'      => 'Entrega en campus',
                    'punto_entrega'  => $request->punto_entrega,
                    'fecha_entrega'  => $request->fecha_entrega,
                    'hora_entrega'   => $request->hora_entrega,
                    'Recibido'       => 0,
                    'Ruta'           => null,
                    'Entregado'      => 0,
                    'id_usuario'     => $id_usuario,
                    'id_producto'    => $producto->id_producto
                ]);

                // Eliminar del carrito
                $item->delete();
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'message' => 'Compra realizada con éxito usando ' . $request->metodo_pago,
                'metodo_pago' => $request->metodo_pago,
                'no_pedido' => $noPedidos,
                'pago_id' => $pago->idPago
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
