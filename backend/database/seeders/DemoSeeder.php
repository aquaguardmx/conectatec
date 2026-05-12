<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // 1. VENDEDORES (3)
        // -------------------------------------------------------
        $vendedores = [
            [
                'nombre'                => 'Carlos Ramírez',
                'Matricula'             => 'V-20230001',
                'Correo_Institucional'  => 'carlos.vendedor@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8110001111',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'vendedor',
            ],
            [
                'nombre'                => 'Sofía López',
                'Matricula'             => 'V-20230002',
                'Correo_Institucional'  => 'sofia.vendedora@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8112223333',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'vendedor',
            ],
            [
                'nombre'                => 'Miguel Torres',
                'Matricula'             => 'V-20230003',
                'Correo_Institucional'  => 'miguel.vendedor@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8114445555',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'vendedor',
            ],
        ];

        $vendedorIds = [];
        foreach ($vendedores as $v) {
            $id = DB::table('Usuario')->insertGetId($v, 'idUsuario');
            $vendedorIds[] = $id;
        }

        // -------------------------------------------------------
        // 2. COMPRADORES (5)
        // -------------------------------------------------------
        $compradores = [
            [
                'nombre'                => 'Ana García',
                'Matricula'             => 'C-20240001',
                'Correo_Institucional'  => 'ana.garcia@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8116667777',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'comprador',
            ],
            [
                'nombre'                => 'Luis Martínez',
                'Matricula'             => 'C-20240002',
                'Correo_Institucional'  => 'luis.martinez@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8118889999',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'comprador',
            ],
            [
                'nombre'                => 'Valeria Hernández',
                'Matricula'             => 'C-20240003',
                'Correo_Institucional'  => 'valeria.hernandez@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8110001112',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'comprador',
            ],
            [
                'nombre'                => 'Diego Flores',
                'Matricula'             => 'C-20240004',
                'Correo_Institucional'  => 'diego.flores@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8112223334',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'comprador',
            ],
            [
                'nombre'                => 'Isabella Ruiz',
                'Matricula'             => 'C-20240005',
                'Correo_Institucional'  => 'isabella.ruiz@orizaba.tecnm.mx',
                'contrasena'            => Hash::make('12345678!'),
                'Telefono'              => '8114445556',
                'Fecha_Registro'        => now(),
                'Rol'                   => 'comprador',
            ],
        ];

        $compradorIds = [];
        foreach ($compradores as $c) {
            $id = DB::table('Usuario')->insertGetId($c, 'idUsuario');
            $compradorIds[] = $id;
        }

        // -------------------------------------------------------
        // 3. PRODUCTOS (6 — 2 por vendedor)
        // -------------------------------------------------------
        $productos = [
            // Carlos Ramírez
            [
                'id_vendedor' => $vendedorIds[0],
                'Nombre'      => 'Apuntes de Cálculo Diferencial',
                'Descripcion' => 'Resumen completo con ejercicios resueltos del semestre. Formato PDF imprimible de 80 páginas.',
                'Precio'      => 120.00,
                'Stock'       => 15,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Apuntes',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_vendedor' => $vendedorIds[0],
                'Nombre'      => 'Libro: Introducción a la Programación',
                'Descripcion' => 'Libro seminuevo en excelentes condiciones. Ideal para primeros semestres de ingeniería.',
                'Precio'      => 250.00,
                'Stock'       => 3,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Libros',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // Sofía López
            [
                'id_vendedor' => $vendedorIds[1],
                'Nombre'      => 'Calculadora Científica Casio FX-991',
                'Descripcion' => 'Calculadora en perfecto estado. Incluye estuche y manual. Imprescindible para ing. y ciencias.',
                'Precio'      => 450.00,
                'Stock'       => 2,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Electrónica',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_vendedor' => $vendedorIds[1],
                'Nombre'      => 'Pack Bolígrafos BIC + Resaltadores',
                'Descripcion' => 'Paquete de 10 bolígrafos azules y 5 resaltadores de colores. Nuevos, en blíster.',
                'Precio'      => 85.00,
                'Stock'       => 20,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Papelería',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // Miguel Torres
            [
                'id_vendedor' => $vendedorIds[2],
                'Nombre'      => 'USB 64GB Kingston Datatraveler',
                'Descripcion' => 'USB nueva sin uso. Entrega en cualquier punto del campus. Color azul/negro.',
                'Precio'      => 180.00,
                'Stock'       => 8,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Electrónica',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_vendedor' => $vendedorIds[2],
                'Nombre'      => 'Mochila Universitaria Antirrobo',
                'Descripcion' => 'Mochila de 30L con puerto USB y cerradura antirrobo. Color negro. Nueva con etiquetas.',
                'Precio'      => 650.00,
                'Stock'       => 4,
                'Imagenes'    => null,
                'Aceptado'    => 1,
                'categoria'   => 'Accesorios',
                'estado'      => 'disponible',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        $productoIds = [];
        foreach ($productos as $p) {
            $id = DB::table('Producto')->insertGetId($p, 'id_producto');
            $productoIds[] = $id;
        }

        // -------------------------------------------------------
        // 4. PEDIDOS con puntos de entrega para mañana
        // -------------------------------------------------------
        $tomorrow = now()->addDay()->format('Y-m-d');
        $noPedido  = time();

        $puntos = [
            'Biblioteca Central',
            'Cafetería Principal',
            'Edificio A — Aulas',
            'Edificio B — Laboratorios',
            'Entrada Principal',
        ];
        $horas = ['10:00', '12:00', '14:00', '16:00'];

        $pedidosData = [
            // Ana García compra Apuntes de Cálculo (de Carlos)
            [
                'NoPedidos'     => $noPedido,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[0],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[0],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[0],
                'id_producto'   => $productoIds[0],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Luis compra Libro Programación (de Carlos)
            [
                'NoPedidos'     => $noPedido + 1,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[1],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[1],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[1],
                'id_producto'   => $productoIds[1],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Valeria compra Calculadora (de Sofía)
            [
                'NoPedidos'     => $noPedido + 2,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[2],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[2],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[2],
                'id_producto'   => $productoIds[2],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Diego compra Pack Bolígrafos (de Sofía)
            [
                'NoPedidos'     => $noPedido + 3,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[3],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[3],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[3],
                'id_producto'   => $productoIds[3],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Isabella compra USB (de Miguel)
            [
                'NoPedidos'     => $noPedido + 4,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[4],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[0],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[4],
                'id_producto'   => $productoIds[4],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // Ana García también compra Mochila (de Miguel)
            [
                'NoPedidos'     => $noPedido + 5,
                'Direccion'     => 'Entrega en campus',
                'punto_entrega' => $puntos[0],
                'fecha_entrega' => $tomorrow,
                'hora_entrega'  => $horas[2],
                'Recibido'      => 0,
                'Ruta'          => null,
                'Entregado'     => 0,
                'id_usuario'    => $compradorIds[0],
                'id_producto'   => $productoIds[5],
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        foreach ($pedidosData as $pedido) {
            DB::table('pedidos')->insert($pedido);
        }

        // -------------------------------------------------------
        // 5. PAGOS asociados
        // -------------------------------------------------------
        $montos = [120.00, 250.00, 450.00, 85.00, 180.00, 650.00];
        foreach ($compradorIds as $i => $compradorId) {
            DB::table('pagos')->insert([
                'id_Usuario'    => $compradorId,
                'Metodo_Pago'   => ($i % 2 === 0) ? 'tarjeta' : 'efectivo',
                'Nombre_Tarjeta'=> ($i % 2 === 0) ? ($compradores[$i]['nombre']) : null,
                'Numero_Tarjeta'=> ($i % 2 === 0) ? '4242424242424242' : null,
                'Monto'         => $montos[$i] ?? 100.00,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('✅ Datos demo cargados correctamente:');
        $this->command->info('   - 3 vendedores creados');
        $this->command->info('   - 5 compradores creados');
        $this->command->info('   - 6 productos creados');
        $this->command->info('   - 6 pedidos para mañana (' . $tomorrow . ')');
        $this->command->info('');
        $this->command->info('🔑 Credenciales (todas usan contraseña: 12345678!)');
        $this->command->info('   VENDEDORES:');
        $this->command->info('   - carlos.vendedor@orizaba.tecnm.mx');
        $this->command->info('   - sofia.vendedora@orizaba.tecnm.mx');
        $this->command->info('   - miguel.vendedor@orizaba.tecnm.mx');
        $this->command->info('   COMPRADORES:');
        $this->command->info('   - ana.garcia@orizaba.tecnm.mx');
        $this->command->info('   - luis.martinez@orizaba.tecnm.mx');
        $this->command->info('   - valeria.hernandez@orizaba.tecnm.mx');
        $this->command->info('   - diego.flores@orizaba.tecnm.mx');
        $this->command->info('   - isabella.ruiz@orizaba.tecnm.mx');
    }
}
