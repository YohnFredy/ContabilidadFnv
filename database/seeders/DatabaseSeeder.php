<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);

        $user = User::firstOrCreate(
            ['email' => 'fredy.guapacha@gmail.com'],
            [
                'name' => 'fredy',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole(\App\Enums\UserRole::ADMIN);

        $this->call(NomenclatureSeeder::class);
    }
}
