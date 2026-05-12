<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::where('id_vendedor', Auth::id())->get();
        return response()->json([
            'success' => true,
            'data' => $productos
        ], 200);
    }

    public function publicIndex()
    {
        // 1 significa aceptado/activo
        $productos = Producto::with('vendedor')->where('Aceptado', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $productos
        ], 200);
    }

    public function publicShow($id)
    {
        $producto = Producto::with('vendedor')->where('Aceptado', 1)->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $producto
        ], 200);
    }

    public function store(Request $request)
    {
        // 1. Validar (Asegúrate de que los nombres coincidan exactamente con lo que envía Nuxt)
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:100',
            'categoria' => 'required|string|max:100', // <-- Categoría agregada aquí
            'Estado' => 'required|string|max:50',
            'Descripcion' => 'required|string',
            'Precio' => 'required|numeric|min:0.01',
            'Stock' => 'required|integer|min:0',
            'Imagenes' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // 2. Asignar valores automáticos o calculados

        // Mapear Estado a estado
        $validatedData['estado'] = $validatedData['Estado'];
        unset($validatedData['Estado']);

        // Asigna el ID del vendedor usando el usuario autenticado por la API (Sanctum/Passport)
        $validatedData['id_vendedor'] = Auth::id();

        $validatedData['Aceptado'] = 1; // Por defecto no aceptado

        // NUEVO: Manejar la subida del archivo
        if ($request->hasFile('Imagenes')) {
            // Guarda el archivo en storage/app/public/productos
            $rutaImagen = $request->file('Imagenes')->store('productos', 'public');
            // Guardamos solo la ruta en la base de datos (ej: 'productos/imagen123.jpg')
            $validatedData['Imagenes'] = $rutaImagen;
        } else {
            $validatedData['Imagenes'] = null;
        }

        // 3. Crear el registro
        try {
            $producto = Producto::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Producto registrado con éxito',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        $producto = Producto::where('id_vendedor', Auth::id())->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $producto
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::where('id_vendedor', Auth::id())->findOrFail($id);

        $validatedData = $request->validate([
            'Nombre' => 'sometimes|required|string|max:100',
            'categoria' => 'sometimes|required|string|max:100',
            'Estado' => 'sometimes|required|string|max:50',
            'Descripcion' => 'sometimes|required|string',
            'Precio' => 'sometimes|required|numeric|min:0.01',
            'Stock' => 'sometimes|required|integer|min:0',
            'Imagenes' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if (isset($validatedData['Estado'])) {
            $validatedData['estado'] = $validatedData['Estado'];
            unset($validatedData['Estado']);
        }

        if ($request->hasFile('Imagenes')) {
            $rutaImagen = $request->file('Imagenes')->store('productos', 'public');
            $validatedData['Imagenes'] = $rutaImagen;
        }

        $producto->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado con éxito',
            'data' => $producto
        ], 200);
    }

    public function destroy($id)
    {
        $producto = Producto::where('id_vendedor', Auth::id())->findOrFail($id);
        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ], 200);
    }
}
