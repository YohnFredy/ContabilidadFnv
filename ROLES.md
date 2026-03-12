# Gestión de Roles y Permisos

Este proyecto utiliza `spatie/laravel-permission` para manejar roles y permisos.

## Estructura

- **Roles**: Definidos en `app/Enums/UserRole.php`.
- **Seeder**: `database/seeders/RolesAndPermissionsSeeder.php` contiene la definición inicial de roles y permisos.
- **Modelo**: `App\Models\User` utiliza el trait `HasRoles`.

## Cómo agregar nuevos Roles o Permisos

La forma más profesional y segura de agregar roles o permisos es a través del Seeder. Esto asegura que todos los desarrolladores y entornos (producción, staging) tengan la misma configuración.

1.  Abre `database/seeders/RolesAndPermissionsSeeder.php`.
2.  Agrega el nuevo permiso al array `$permissions`.
3.  Si es un nuevo rol, agrégalo primero en `app/Enums/UserRole.php` y luego defínelo en el Seeder.
4.  Asigna los permisos al rol correspondiente en el Seeder.

Ejemplo:

```php
// En RolesAndPermissionsSeeder.php

$permissions = [
    // ... permisos existentes
    'nuevo permiso', // <--- Agregar aquí
];

// ...

$role = Role::firstOrCreate(['name' => 'nuevo_rol']);
$role->givePermissionTo(['nuevo permiso']);
```

5.  Ejecuta el seeder:
    ```bash
    php artisan db:seed --class=RolesAndPermissionsSeeder
    ```

## Gestión de Usuarios (UI)

Puedes asignar roles a los usuarios desde la interfaz administrativa:
1.  Ve a **Administración > Usuarios** en el menú lateral.
2.  Haz clic en "Editar" en un usuario.
3.  Selecciona los roles deseados y guarda.

## Uso en Código

Para proteger rutas o lógica:

```php
// En Rutas
Route::get('/admin', ...)->middleware(['can:manage users']);

// En Blade
@can('manage users')
    <button>Eliminar</button>
@endcan

// En PHP
if ($user->can('manage users')) {
    // ...
}
```
