# 🚀 QUICK START - Inicia en 5 minutos

Esta es la guía más rápida para poner el módulo Player Notes funcionando.

## ✅ Pre-requisitos

- ✓ Laravel 10+
- ✓ Livewire 3+
- ✓ Spatie Permission instalado
- ✓ Base de datos configurada

## 🎯 5 Pasos Rápidos

### Paso 1️⃣: Copiar archivos

Copia todos los archivos del módulo manteniéndolos en sus respectivas carpetas.

```
app/Models/PlayerNote.php
app/Http/Livewire/PlayerNotes.php
app/Repositories/*
app/Policies/PlayerNotePolicy.php
database/migrations/2026_06_19_000000_create_player_notes_table.php
database/factories/PlayerNoteFactory.php
database/seeders/PermissionSeeder.php
resources/views/livewire/player-notes.blade.php
resources/views/players/show.blade.php
tests/Feature/PlayerNoteTest.php
```

### Paso 2️⃣: Ejecutar migraciones

```bash
php artisan migrate
php artisan db:seed --class=PermissionSeeder
```

### Paso 3️⃣: Registrar la política

En `app/Providers/AuthServiceProvider.php`:

```php
use App\Models\PlayerNote;
use App\Policies\PlayerNotePolicy;

protected $policies = [
    PlayerNote::class => PlayerNotePolicy::class,
];
```

### Paso 4️⃣: Agregar ruta

En `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/players/{player}/notes', function (\App\Models\User $player) {
        return view('players.show', ['player' => $player]);
    })->name('players.notes');
});
```

### Paso 5️⃣: Dar permisos al usuario

```bash
php artisan tinker
>>> $user = User::find(1);  // Tu usuario
>>> $user->givePermissionTo('create player notes');
```

## ✨ ¡Listo! Ahora puedes:

1. Ir a: `http://localhost:8000/players/1/notes`
2. Crear notas
3. Ver notas
4. Eliminar notas

## 🧪 Verificar que funciona

```bash
# Ejecutar tests
php artisan test tests/Feature/PlayerNoteTest.php

# Debería mostrar: "PASS Tests\Feature\PlayerNoteTest"
```

## 📝 Ejemplo en una vista

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Jugador: {{ $player->name }}</h1>

    <livewire:player-notes :playerId="$player->id" />
</div>
@endsection
```

## 🔍 Verificar que AppServiceProvider está actualizado

En `app/Providers/AppServiceProvider.php`, verifica que tenga:

```php
use App\Repositories\PlayerNoteRepository;
use App\Repositories\PlayerNoteRepositoryInterface;

public function register(): void
{
    $this->app->bind(
        PlayerNoteRepositoryInterface::class,
        PlayerNoteRepository::class
    );
}
```

## 🎓 Características activadas

✅ Crear notas
✅ Ver historial
✅ Eliminar notas
✅ Validación automática
✅ Permisos de roles
✅ Reactividad Livewire
✅ Timestamps automáticos

## 📚 Documentación completa

- `PLAYER_NOTES_README.md` - Guía completa
- `PLAYER_NOTES_SETUP.md` - Pasos detallados
- `PLAYER_NOTES_USEFUL_COMMANDS.md` - Comandos útiles

## ❓ Si algo no funciona

1. Ejecuta: `php artisan cache:clear && php artisan config:clear`
2. Verifica: `php artisan migrate:status`
3. Revisa: `storage/logs/laravel.log`
4. Prueba: `php artisan test tests/Feature/PlayerNoteTest.php`

---

**¡Eso es todo! El módulo debería estar funcionando.** 🎉
