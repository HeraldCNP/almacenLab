<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            ['nombre_proveedor' => 'Química Minera Ltda', 'contacto_proveedor' => 'Juan Pérez', 'telefono' => '555-0101', 'email' => 'ventas@quimicaminera.com'],
            ['nombre_proveedor' => 'LabSupply Chile', 'contacto_proveedor' => 'Maria Rodriguez', 'telefono' => '555-0202', 'email' => 'contacto@labsupply.cl'],
            ['nombre_proveedor' => 'Importadora Científica', 'contacto_proveedor' => 'Carlos Gomez', 'telefono' => '555-0303', 'email' => 'carlos@cientifica.com'],
            ['nombre_proveedor' => 'Reactivos del Norte', 'contacto_proveedor' => 'Ana Torres', 'telefono' => '555-0404', 'email' => 'ana@reactivosnorte.cl'],
        ];

        foreach ($proveedores as $proveedor) {
            \App\Models\Proveedor::create($proveedor);
        }
    }
}
