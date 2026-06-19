# 🔧 GUÍA DE INTEGRACIÓN - Modificaciones en Archivos Existentes

Esta guía muestra exactamente qué modificar en archivos que ya existen en tu proyecto Laravel.

---

## 1️⃣ Actualizar `app/Providers/AuthServiceProvider.php`

### Ubicación

```
app/Providers/AuthServiceProvider.php
```

### Qué agregar

Abre el archivo y agrega el import y la política:

```php
<?php

namespace App\Providers;

use App\Models\PlayerNote;                    // ← AGREGAR ESTA LÍNEA
use App\Policies\PlayerNotePolicy;            // ← AGREGAR ESTA LÍNEA
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PlayerNote::class => PlayerNotePolicy::class,  // ← AGREGAR ESTA LÍNEA
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

### Explicación

- Importas el modelo `PlayerNote`
- Importas la política `PlayerNotePolicy`
- Registras la relación en el array `$policies`

---

## 2️⃣ Actualizar `app/Models/User.php`

### Ubicación

```
app/Models/User.php
```

### Qué agregar

Busca la clase User y agrega las relaciones:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;  // ← AGREGAR IMPORT
use Illuminate\Foundation\Auth\User as Authenticatable;
// ... otros imports ...

class User extends Authenticatable
{
    // ... resto del código ...

    /**
     * Get the player notes authored by this user.
     *
     * @return HasMany
     */
    public function playerNotes(): HasMany
    {
        return $this->hasMany(PlayerNote::class, 'author_id');
    }

    /**
     * Get the player notes for this user (notes about them).
     *
     * @return HasMany
     */
    public function receivedPlayerNotes(): HasMany
    {
        return $this->hasMany(PlayerNote::class, 'player_id');
    }
}
```

### Uso en vistas

```blade
{{-- Ver todas las notas que ha creado el usuario --}}
@foreach($user->playerNotes as $note)
    {{ $note->note }}
@endforeach

{{-- Ver todas las notas sobre el usuario --}}
{{ $user->receivedPlayerNotes()->count() }} notas
```

---

## 3️⃣ Actualizar `routes/web.php`

### Ubicación

```
routes/web.php
```

### Qué agregar

Agrega esta ruta en el grupo `auth()`:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ↓ ↓ ↓ AGREGAR ESTE BLOQUE ↓ ↓ ↓

Route::middleware(['auth'])->group(function () {
    // Ruta para ver notas de un jugador
    Route::get('/players/{player}/notes', function (\App\Models\User $player) {
        return view('players.show', ['player' => $player]);
    })->name('players.notes');
});

// ↑ ↑ ↑ FIN DEL BLOQUE ↑ ↑ ↑

// Si prefieres usar controlador:
// Route::get('/players/{player}', [PlayerNoteController::class, 'show'])
//     ->name('players.show')
//     ->middleware('auth');
```

### URLs disponibles después

- `http://localhost:8000/players/1/notes` - Ver notas del jugador con ID 1
- `http://localhost:8000/players/2/notes` - Ver notas del jugador con ID 2

---

## 4️⃣ Actualizar `config/app.php` (OPCIONAL)

### Ubicación

```
config/app.php
```

### Qué verificar

Busca el array `providers` y asegúrate que estos proveedores estén:

```php
'providers' => [
    // Laravel Framework Service Providers...
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    // ... otros ...

    // Package Service Providers...
    Spatie\Permission\PermissionServiceProvider::class,  // ← Debe estar si instalaste Spatie

    // Application Service Providers...
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
],
```

---

## 5️⃣ Actualizar `resources/views/layouts/app.blade.php`

### Ubicación

```
resources/views/layouts/app.blade.php
```

### Qué verificar

En la sección `<head>`, debe tener:

```blade
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- ... otros tags ... -->

        <!-- ← Verificar que esté Livewire Styles -->
        @livewireStyles
    </head>
    <body>
        <!-- ... contenido ... -->

        <!-- ← Verificar que esté al final -->
        @livewireScripts
    </body>
</html>
```

Si NO está, agrega estas líneas:

- En `<head>`: `@livewireStyles`
- Antes de `</body>`: `@livewireScripts`

---

## 6️⃣ Crear `.env` (si necesita configuración)

### Ubicación

```
.env
```

### Qué verificar

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=root
DB_PASSWORD=
```

Ejecuta:

```bash
php artisan migrate
php artisan db:seed --class=PermissionSeeder
```

---

## 7️⃣ Crear vista de prueba (OPCIONAL)

### Ubicación

```
resources/views/players/index.blade.php
```

### Qué crear

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Jugadores</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($players as $player)
            <a href="{{ route('players.notes', $player) }}" class="block p-4 bg-white rounded shadow hover:shadow-md transition">
                <h2 class="font-bold">{{ $player->name }}</h2>
                <p class="text-gray-600 text-sm">{{ $player->email }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    {{ $player->receivedPlayerNotes()->count() }} notas
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection
```

---

## 8️⃣ Agregar ruta para listar (OPCIONAL)

### En `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    // Listar todos los jugadores
    Route::get('/players', function () {
        return view('players.index', [
            'players' => \App\Models\User::all()
        ]);
    })->name('players.index');

    // Ver notas de un jugador
    Route::get('/players/{player}/notes', function (\App\Models\User $player) {
        return view('players.show', ['player' => $player]);
    })->name('players.notes');
});
```

---

## 📋 Checklist de Modificaciones

```
✓ Copiar todos los archivos nuevos
✓ Actualizar AuthServiceProvider.php
✓ Actualizar User.php con relaciones
✓ Actualizar routes/web.php
✓ Verificar Livewire en layout
✓ Ejecutar: php artisan migrate
✓ Ejecutar: php artisan db:seed --class=PermissionSeeder
✓ Dar permiso: php artisan tinker → $user->givePermissionTo('create player notes')
✓ Probar: http://localhost:8000/players/1/notes
✓ Ejecutar tests: php artisan test tests/Feature/PlayerNoteTest.php
```

---

## 🧪 Verificar que todo está integrado

```bash
# 1. Verificar migraciones
php artisan migrate:status

# 2. Verificar permisos en BD
php artisan tinker
>>> Spatie\Permission\Models\Permission::all();

# 3. Ejecutar un test
php artisan test tests/Feature/PlayerNoteTest.php --filter test_authenticated_user_can_create_player_note

# 4. Verificar rutas
php artisan route:list | grep players

# 5. Ver estructura de tabla
php artisan tinker
>>> DB::table('player_notes')->get();
```

---

## 🔍 Solución de Problemas

### "Class not found: PlayerNote"

```
Solución: Ejecuta php artisan cache:clear && php artisan config:clear
```

### "Policy not registered"

```
Solución: Verifica que AuthServiceProvider.php tenga la política registrada
```

### "Tabla no existe"

```
Solución: Ejecuta php artisan migrate
```

### "Permiso denegado"

```
Solución: Ejecuta php artisan db:seed --class=PermissionSeeder
Y: php artisan tinker → $user->givePermissionTo('create player notes')
```

### "Livewire scripts no cargan"

```
Solución: Verifica que en layout.blade.php esté:
- @livewireStyles en <head>
- @livewireScripts antes de </body>
```

---

## 📝 Orden Correcto de Integración

1. **Copiar archivos** (todos los nuevos)
2. **Actualizar AppServiceProvider.php** (ya debe estar hecho)
3. **Actualizar AuthServiceProvider.php** (agregación)
4. **Actualizar User.php** (agregar relaciones)
5. **Ejecutar migraciones** (`php artisan migrate`)
6. **Ejecutar seeder** (`php artisan db:seed --class=PermissionSeeder`)
7. **Actualizar routes/web.php** (agregar rutas)
8. **Verificar layout.blade.php** (Livewire scripts)
9. **Dar permisos** (`php artisan tinker` y `$user->givePermissionTo()`)
10. **Probar** (visitar URL y ejecutar tests)

---

**Sigue esta guía paso a paso y todo debería funcionar perfectamente.** ✨
