<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nomenclature;

class NomenclatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => '1105', 'name' => 'Caja', 'category' => 'Activos Corrientes'],
            ['code' => '1110', 'name' => 'Bancos', 'category' => 'Activos Corrientes'],
            ['code' => '1305', 'name' => 'Clientes', 'category' => 'Activos Corrientes'],
            ['code' => '1435', 'name' => 'Mercancías no fabricadas por la empresa', 'category' => 'Activos Corrientes'],
            ['code' => '2205', 'name' => 'Proveedores nacionales', 'category' => 'Pasivos Corrientes'],
            ['code' => '2365', 'name' => 'Retención en la fuente', 'category' => 'Pasivos Corrientes'],
            ['code' => '2805', 'name' => 'Anticipos y avances recibidos', 'category' => 'Pasivos Corrientes'],
            ['code' => '3105', 'name' => 'Capital suscrito y pagado', 'category' => 'Patrimonio'],
            ['code' => '3115', 'name' => 'Aportes sociales', 'category' => 'Patrimonio'],
            ['code' => '4135', 'name' => 'Venta de mercancías', 'category' => 'Ingresos'],
            ['code' => '4295', 'name' => 'Ingresos no operacionales - Diversos', 'category' => 'Ingresos'],
            ['code' => '5105', 'name' => 'Sueldos y salarios', 'category' => 'Gastos'],
            ['code' => '5145', 'name' => 'Servicios públicos', 'category' => 'Gastos'],
            ['code' => '6135', 'name' => 'Comercio al por mayor y al por menor(costo)', 'category' => 'Costos'],
            ['code' => '132505', 'name' => 'A socios', 'category' => 'Activos Corrientes'],
            ['code' => '133005', 'name' => 'Anticipos A proveedores', 'category' => 'Activos Corrientes'],
            ['code' => '135515', 'name' => 'Anticipo de Impuestos (Retención en la fuente)', 'category' => 'Activos Corrientes'],
            ['code' => '138095', 'name' => 'Deudores Varios (Pasarela de Pago Bold)', 'category' => 'Activos Corrientes'],
            ['code' => '171004', 'name' => 'Organización y preoperativos', 'category' => 'Activos No Corrientes'],
            ['code' => '171016', 'name' => 'Programas para computador (software)', 'category' => 'Activos No Corrientes'],
            ['code' => '233520', 'name' => 'Comisiones por Pagar', 'category' => 'Pasivos Corrientes'],
            ['code' => '233525', 'name' => 'Cuentas por pagar - Honorarios', 'category' => 'Pasivos Corrientes'],
            ['code' => '236515', 'name' => 'Retención en la fuente por pagar', 'category' => 'Pasivos Corrientes'],
            ['code' => '236575', 'name' => 'Autorretenciones por Pagar', 'category' => 'Pasivos Corrientes'],
            ['code' => '240805', 'name' => 'IVA Generado', 'category' => 'Pasivos Corrientes'],
            ['code' => '240810', 'name' => 'IVA Descontable', 'category' => 'Pasivos Corrientes'],
            ['code' => '240825', 'name' => 'Impuesto a las ventas por pagar', 'category' => 'Pasivos Corrientes'],
            ['code' => '421005', 'name' => 'Intereses', 'category' => 'Ingresos'],
            ['code' => '425035', 'name' => 'Ingresos por Ajustes', 'category' => 'Ingresos'],
            ['code' => '511025', 'name' => 'Asesoría jurídica', 'category' => 'Gastos'],
            ['code' => '514015', 'name' => 'Trámites y licencias', 'category' => 'Gastos'],
            ['code' => '519530', 'name' => 'Útiles, papelería y fotocopias', 'category' => 'Gastos'],
            ['code' => '519560', 'name' => 'Casino y restaurante', 'category' => 'Gastos'],
            ['code' => '520518', 'name' => 'Comisiones (Gasto de Ventas)', 'category' => 'Gastos'],
            ['code' => '529535', 'name' => 'Combustibles y lubricantes', 'category' => 'Gastos'],
            ['code' => '530505', 'name' => 'Gastos Financieros (4x1000)', 'category' => 'Gastos'],
            ['code' => '530510', 'name' => 'Gastos Financieros (Cuota Manejo)', 'category' => 'Gastos'],
            ['code' => '530515', 'name' => 'Gastos Financieros - Comisiones', 'category' => 'Gastos'],
            ['code' => '531520', 'name' => 'Impuestos asumidos Gasto Ajuste Aproximación', 'category' => 'Gastos'],
        ];

        foreach ($data as $item) {
            Nomenclature::updateOrCreate(
                ['code' => $item['code']],
                ['name' => $item['name'], 'category' => $item['category']]
            );
        }
    }
}
