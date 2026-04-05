<?php

namespace Database\Seeders;

use App\Models\AccountingRule;
use App\Models\AccountingRuleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorizeExistingRulesSeeder extends Seeder
{
    public function run()
    {
        // Define professional categories
        $categories = [
            'Ingresos y Ventas' => 'Reglas para el registro de ventas, facturación e ingresos principales.',
            'Compras y Costos' => 'Registro de compra de productos, fletes y costos directos.',
            'Gastos Administrativos y Operativos' => 'Papelería, honorarios, combustible, seguros, representación, etc.',
            'Gastos Financieros' => 'Cuotas de manejo, 4x1000, comisiones bancarias.',
            'Impuestos y Retenciones' => 'IVA, autorretenciones, ajustes de impuestos.',
            'Tesorería y Movimientos Bancarios' => 'Traslados entre caja y bancos, pasarelas de pago.',
            'Anticipos' => 'Anticipos de clientes, a proveedores o a socios.',
            'Cuentas por Pagar (Pasivos)' => 'Comisiones por pagar, honorarios por pagar, causaciones.',
            'Ajustes y Notas Contables' => 'Notas crédito, ajustes de cartera, correcciones.',
        ];

        // Map rule IDs to the category names
        $mapping = [
            // Ingresos y Ventas
            6 => 'Ingresos y Ventas',
            7 => 'Ingresos y Ventas',

            // Compras y Costos
            8 => 'Compras y Costos',
            23 => 'Compras y Costos',
            27 => 'Compras y Costos',

            // Gastos Administrativos y Operativos
            9 => 'Gastos Administrativos y Operativos',
            16 => 'Gastos Administrativos y Operativos',
            18 => 'Gastos Administrativos y Operativos',
            21 => 'Gastos Administrativos y Operativos',
            26 => 'Gastos Administrativos y Operativos',
            29 => 'Gastos Administrativos y Operativos',
            30 => 'Gastos Administrativos y Operativos',

            // Gastos Financieros
            5 => 'Gastos Financieros',
            17 => 'Gastos Financieros',

            // Impuestos y Retenciones
            13 => 'Impuestos y Retenciones',
            14 => 'Impuestos y Retenciones',
            15 => 'Impuestos y Retenciones',
            25 => 'Impuestos y Retenciones',

            // Tesorería y Movimientos Bancarios
            2 => 'Tesorería y Movimientos Bancarios',
            3 => 'Tesorería y Movimientos Bancarios', // Banco interes ganado
            10 => 'Tesorería y Movimientos Bancarios',
            19 => 'Tesorería y Movimientos Bancarios',
            20 => 'Tesorería y Movimientos Bancarios',

            // Anticipos
            1 => 'Anticipos',
            4 => 'Anticipos',
            22 => 'Anticipos',
            31 => 'Anticipos',

            // Cuentas por Pagar (Pasivos)
            11 => 'Cuentas por Pagar (Pasivos)',
            24 => 'Cuentas por Pagar (Pasivos)',
            28 => 'Cuentas por Pagar (Pasivos)', // Mercancía recibida no facturada

            // Ajustes y Notas Contables
            12 => 'Ajustes y Notas Contables',
        ];

        DB::transaction(function () use ($categories, $mapping) {
            $this->command->info('Creando categorias profesionales...');
            $categoryModels = [];
            foreach ($categories as $name => $desc) {
                $categoryModels[$name] = AccountingRuleCategory::firstOrCreate(
                    ['name' => $name],
                    ['description' => $desc]
                );
            }

            $this->command->info('Asignando categorias a las reglas existentes...');
            $count = 0;
            foreach ($mapping as $ruleId => $categoryName) {
                $rule = AccountingRule::find($ruleId);
                if ($rule) {
                    $rule->accounting_rule_category_id = $categoryModels[$categoryName]->id;
                    $rule->save();
                    $count++;
                }
            }
            $this->command->info("¡$count reglas actualizadas exitosamente!");
        });
    }
}
