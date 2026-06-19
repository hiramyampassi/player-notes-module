# 🎯 Guía Completa de Configuración - Módulo Player Notes

Este documento proporciona un paso a paso detallado para integrar completamente el módulo Player Notes en tu proyecto Laravel.

## ✅ Checklist de Instalación

- [ ] Copiar todos los archivos generados
- [ ] Ejecutar migraciones
- [ ] Instalar dependencias (si falta)
- [ ] Crear permisos en la base de datos
- [ ] Registrar la política
- [ ] Actualizar modelos relacionados
- [ ] Configurar Livewire
- [ ] Ejecutar tests
- [ ] Implementar en vistas

---

## 📋 Paso a Paso Detallado

### 1. Verificar estructura de archivos generados

Asegúrate de que estos archivos existan en tu proyecto:

```
✓ app/Models/PlayerNote.php
✓ app/Http/Livewire/PlayerNotes.php
✓ app/Repositories/PlayerNoteRepositoryInterface.php
✓ app/Repositories/PlayerNoteRepository.php
✓ app/Policies/PlayerNotePolicy.php
✓ app/Providers/AppServiceProvider.php (modificado)
✓ database/migrations/2026_06_19_000000_create_player_notes_table.php
✓ database/factories/PlayerNoteFactory.php
✓ resources/views/livewire/player-notes.blade.php
✓ tests/Feature/PlayerNoteTest.php
✓ PLAYER_NOTES_README.md
✓ PLAYER_NOTES_SETUP.md (este archivo)
```

### 2. Ejecutar migraciones

```bash
# Ver el estado de migraciones
php artisan migrate:status

# Ejecutar todas las migraciones pendientes
php artisan migrate

# Si necesitas rollback
php artisan migrate:rollback
```

Verifica que la tabla `player_notes` se haya creado:

```bash
php artisan tinker
>>> DB::table('player_notes')->count()
0
```

### 3. Instalar dependencias requeridas

Si tu proyecto no tiene Spatie Permission:

```bash
composer require spatie/laravel-permission

# Publicar migraciones
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

# Correr migraciones
php artisan migrate
```

### 4. Configurar AuthServiceProvider

En `app/Providers/AuthServiceProvider.php`, agrega:

```php
<?php

namespace App\Providers;

use App\Models\PlayerNote;
use App\Policies\PlayerNotePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PlayerNote::class => PlayerNotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
```

### 5. Actualizar modelo User

En `app/Models/User.php`, agrega estas relaciones (si no existen):

```php
<?php

namespace App\Models;

// ... otros imports ...
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    // ... resto del código ...

    /**
     * Get the player notes authored by this user.
     */
    public function playerNotes(): HasMany
    {
        return $this->hasMany(PlayerNote::class, 'author_id');
    }

    /**
     * Get the player notes for this user (notes about them).
     */
    public function receivedPlayerNotes(): HasMany
    {
        return $this->hasMany(PlayerNote::class, 'player_id');
    }
}
```

### 6. Crear permisos

Opción A: Usar Tinker

```bash
php artisan tinker
```

```php
>>> use Spatie\Permission\Models\Permission;
>>> use Spatie\Permission\Models\Role;

// Crear permisos
>>> Permission::firstOrCreate(['name' => 'create player notes']);
>>> Permission::firstOrCreate(['name' => 'delete player notes']);

// Opcional: crear rol y asignar permisos
>>> $role = Role::firstOrCreate(['name' => 'staff']);
>>> $role->givePermissionTo('create player notes');
```

Opción B: Crear un Seeder

```bash
php artisan make:seeder PermissionSeeder
```

En `database/seeders/PermissionSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        Permission::firstOrCreate(['name' => 'create player notes']);
        Permission::firstOrCreate(['name' => 'delete player notes']);

        // Crear rol staff y asignar permisos
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->syncPermissions([
            'create player notes',
            'delete player notes',
        ]);

        // Crear rol admin (si no existe) con todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
```

Ejecutar el seeder:

```bash
php artisan db:seed --class=PermissionSeeder
```

### 7. Verificar configuración de Livewire

En tu layout principal (`resources/views/layouts/app.blade.php`), asegúrate de tener:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ... -->
    @livewireStyles
</head>
<body>
    <!-- ... contenido ... -->

    @livewireScripts
</body>
</html>
```

### 8. Crear vista de prueba

Crea `resources/views/players/show.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $player->name }}</h1>
        <p class="text-gray-600 mt-2">Email: {{ $player->email }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <livewire:player-notes :playerId="$player->id" />
    </div>
</div>
@endsection
```

### 9. Crear una ruta de prueba

En `routes/web.php`:

```php
Route::get('/players/{player}', function (\App\Models\User $player) {
    return view('players.show', ['player' => $player]);
})->middleware('auth')->name('players.show');
```

### 10. Asignar permisos a usuarios

Para pruebas locales:

```bash
php artisan tinker
```

```php
>>> $user = User::find(1);
>>> $user->givePermissionTo('create player notes');
>>> $user->givePermissionTo('delete player notes');

// Verificar
>>> $user->hasPermissionTo('create player notes')
true
```

---

## 🧪 Ejecutar Tests

```bash
# Ejecutar todos los tests del módulo
php artisan test tests/Feature/PlayerNoteTest.php

# Ejecutar un test específico
php artisan test tests/Feature/PlayerNoteTest.php --filter test_authenticated_user_can_create_player_note

# Ejecutar con salida verbosa
php artisan test tests/Feature/PlayerNoteTest.php -v

# Ejecutar con coverage
php artisan test tests/Feature/PlayerNoteTest.php --coverage
```

### Resultados esperados

Todos los tests deben pasar:

```
PASS  Tests\Feature\PlayerNoteTest
  ✓ authenticated user can create player note
  ✓ player note is saved correctly
  ✓ validation fails when note is empty
  ✓ validation fails when note exceeds max length
  ✓ can retrieve notes for specific player
  ✓ user can delete own note
  ✓ player note relationships

Tests:    7 passed
Duration: 0.25s
```

---

## 🔧 Configuración Avanzada

### Cambiar límite de caracteres

En `app/Http/Livewire/PlayerNotes.php`:

```php
protected array $rules = [
    'note' => 'required|string|max:1000', // Cambiar de 500 a 1000
    'playerId' => 'required|exists:users,id',
];
```

En `resources/views/livewire/player-notes.blade.php`:

```blade
<textarea maxlength="1000" ...></textarea>
```

### Agregar más campos a las notas

1. Crear migración:

    ```bash
    php artisan make:migration add_priority_to_player_notes_table
    ```

2. Editar la migración:

    ```php
    public function up(): void
    {
        Schema::table('player_notes', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
        });
    }
    ```

3. Ejecutar: `php artisan migrate`

4. Actualizar `PlayerNote.php`:

    ```php
    protected $fillable = ['player_id', 'author_id', 'note', 'priority'];
    ```

5. Actualizar componente Livewire y vista

### Personalizar mensajes de validación

En `app/Http/Livewire/PlayerNotes.php`:

```php
protected array $messages = [
    'note.required' => 'La nota es obligatoria.',
    'note.max' => 'La nota no puede exceder 500 caracteres.',
    'note.string' => 'La nota debe ser texto válido.',
    'playerId.required' => 'Debes seleccionar un jugador.',
    'playerId.exists' => 'El jugador seleccionado no existe.',
];
```

---

## 🐛 Debugging

### Ver logs de Livewire

Activar debug en `config/livewire.php`:

```php
'debug' => env('APP_DEBUG', false),
```

### Inspeccionar datos en tiempo real

En `app/Http/Livewire/PlayerNotes.php`:

```php
public function saveNote(): void
{
    \Log::debug('Player ID: ' . $this->playerId);
    \Log::debug('Note: ' . $this->note);
    \Log::debug('User: ' . auth()->id());

    // ... resto del código
}
```

Revisar logs:

```bash
tail -f storage/logs/laravel.log
```

### Verificar permisos

```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->getAllPermissions();
>>> $user->hasPermissionTo('create player notes')
>>> auth()->user()->can('create', PlayerNote::class)
```

---

## 📦 Estructura del Proyecto Actualizado

```
player-notes-module-hiram/
├── app/
│   ├── Models/
│   │   ├── PlayerNote.php              ✓ Nuevo
│   │   └── User.php                    ✓ Modificado
│   ├── Http/
│   │   └── Livewire/
│   │       └── PlayerNotes.php         ✓ Nuevo
│   ├── Repositories/
│   │   ├── PlayerNoteRepositoryInterface.php  ✓ Nuevo
│   │   └── PlayerNoteRepository.php           ✓ Nuevo
│   ├── Policies/
│   │   └── PlayerNotePolicy.php        ✓ Nuevo
│   └── Providers/
│       ├── AppServiceProvider.php      ✓ Modificado
│       └── AuthServiceProvider.php     ✓ Modificado
├── database/
│   ├── migrations/
│   │   └── 2026_06_19_000000_create_player_notes_table.php  ✓ Nuevo
│   ├── seeders/
│   │   ├── PermissionSeeder.php        ✓ Nuevo
│   │   └── DatabaseSeeder.php          ✓ Modificado (opcional)
│   └── factories/
│       └── PlayerNoteFactory.php       ✓ Nuevo
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php           ✓ Modificado
│   │   ├── livewire/
│   │   │   └── player-notes.blade.php  ✓ Nuevo
│   │   └── players/
│   │       └── show.blade.php          ✓ Nuevo (ejemplo)
├── tests/
│   └── Feature/
│       └── PlayerNoteTest.php          ✓ Nuevo
├── PLAYER_NOTES_README.md              ✓ Nuevo
└── PLAYER_NOTES_SETUP.md               ✓ Este archivo
```

---

## ✨ Siguientes Pasos

1. **Implementar en tu aplicación**: Usa `<livewire:player-notes :playerId="$player->id" />` donde necesites
2. **Personalizar estilos**: Ajusta Tailwind CSS según tu diseño
3. **Agregar más funcionalidades**: Editar, filtrar, exportar notas
4. **Configurar búsqueda**: Implementar búsqueda de notas
5. **Auditoría**: Registrar cambios en las notas

---

## 🆘 Soporte

Si tienes problemas:

1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica la migración: `php artisan migrate:status`
3. Confirma permisos: `php artisan tinker`
4. Ejecuta tests: `php artisan test`
5. Limpia cache: `php artisan cache:clear && php artisan config:clear`

---

**¡El módulo está listo para usar!** 🎉
