<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Enums\UserRole;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'gestionar usuarios',
            'ver inventario',
            'gestionar inventario',
            'ver contabilidad',
            'gestionar contabilidad',
            'crear asientos de diario',
            'editar asientos de diario',
            'eliminar asientos de diario',
            'crear movimientos de inventario',
            'editar movimientos de inventario',
            'eliminar movimientos de inventario',
            'registrar ventas',
            'crear productos',
            'omitir restriccion de fecha',
            'crear reglas contables',
            'editar reglas contables',
            'eliminar reglas contables',
            'crear nomenclatura',
            'editar nomemclatura',
            'eliminar nomemclatura',
            'dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign existing permissions
        /* $role1 = Role::firstOrCreate(['name' => UserRole::ADMIN->value]);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::firstOrCreate(['name' => UserRole::ACCOUNTANT->value]);
        $role2->givePermissionTo([
            'ver inventario',
            'ver contabilidad',
            'gestionar contabilidad',
            'crear asientos de diario',
            'editar asientos de diario',
            'crear productos',
            'crear movimientos de inventario',
            'registrar ventas',
            'editar movimientos de inventario',
            'crear reglas contables',
            'editar reglas contables',
            'crear nomenclatura',
        ]);

        $role3 = Role::firstOrCreate(['name' => UserRole::USER->value]);
        $role3->givePermissionTo([
            'ver inventario',
        ]); */


        // Create a default admin user if one doesn't exist (optional, but requested "practical")
        // We'll leave the user creation to the User factory or manual seeding, 
        // but getting the first user and making them admin is a common "dev" convenience.
        // Let's NOT auto-assign to user ID 1 automatically in production, but for this task 
        // "organiza todo... para cuando yo crea roles" implies setting up the system.
        
        // I will add a commented out section for assigning to a user.

        // php artisan db:seed --class=RolesAndPermissionsSeeder
    }
}
