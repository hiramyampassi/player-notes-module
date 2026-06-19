# Player Notes Module - Módulo de Historial de Notas de Jugador

Este es un módulo completo para Laravel con Livewire que permite crear y visualizar notas internas sobre jugadores específicos. El módulo sigue las mejores prácticas de Laravel, implementa el patrón de Repositorio, y utiliza permisos basados en roles de Spatie/laravel-permission.

## 📋 Características

- ✅ Crear notas internas para jugadores específicos
- ✅ Visualizar historial de notas ordenadas por fecha (más recientes primero)
- ✅ Eliminar notas (solo el autor o administrador)
- ✅ Validación completa de formularios (requerido, máximo 500 caracteres)
- ✅ Sistema de permisos con Spatie/laravel-permission
- ✅ Componente Livewire reactivo sin recargas de página
- ✅ Patrón de Repositorio para separación de responsabilidades
- ✅ Pruebas unitarias e integración
- ✅ Type hints completos con PHP 8+
- ✅ Interfaz responsiva con Tailwind CSS

## 🏗️ Arquitectura

```
app/
├── Models/
│   ├── PlayerNote.php              # Modelo con relaciones
│   └── User.php                    # Modelo de usuario (existente)
├── Repositories/
│   ├── PlayerNoteRepositoryInterface.php  # Interfaz del repositorio
│   └── PlayerNoteRepository.php           # Implementación del repositorio
├── Http/
│   └── Livewire/
│       └── PlayerNotes.php         # Componente Livewire
├── Policies/
│   └── PlayerNotePolicy.php        # Política de autorización
└── Providers/
    └── AppServiceProvider.php      # Registro de servicios

database/
├── migrations/
│   └── create_player_notes_table.php  # Migración de tabla
└── factories/
    └── PlayerNoteFactory.php          # Factory para tests

resources/
└── views/
    └── livewire/
        └── player-notes.blade.php  # Vista del componente

tests/
└── Feature/
    └── PlayerNoteTest.php          # Tests de feature
```

## 📦 Requisitos

- **PHP**: 8.1 o superior
- **Laravel**: 10.0 o superior
- **Livewire**: 3.0 o superior
- **Spatie Laravel Permission**: Última versión compatible
- **Tailwind CSS**: Para estilos (recomendado)

## 🚀 Instalación

### Paso 1: Copiar archivos

Copia todos los archivos del módulo en tu proyecto Laravel respetando la estructura de directorios.

### Paso 2: Instalar dependencias (si falta)

```bash
composer require livewire/livewire
composer require spatie/laravel-permission
```

### Paso 3: Ejecutar migraciones

```bash
php artisan migrate
```

### Paso 4: Crear permisos

En tu seeder o en la consola Laravel Tinker:

```php
php artisan tinker

>>> use Spatie\Permission\Models\Permission;
>>> Permission::create(['name' => 'create player notes']);
>>> Permission::create(['name' => 'delete player notes']);
```

O crear un seeder:

```php
php artisan make:seeder PermissionSeeder
```

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create player notes']);
        Permission::firstOrCreate(['name' => 'delete player notes']);
    }
}
```

Luego ejecutar:

```bash
php artisan db:seed --class=PermissionSeeder
```

### Paso 5: Registrar la Política

En tu `AuthServiceProvider.php`:

```php
use App\Models\PlayerNote;
use App\Policies\PlayerNotePolicy;

protected $policies = [
    PlayerNote::class => PlayerNotePolicy::class,
];
```

## 🎯 Uso

### En Blade

Para mostrar el componente Livewire en una vista:

```blade
<livewire:player-notes :playerId="$playerId" />
```

O con la sintaxis de Blade:

```blade
<livewire:player-notes playerId="{{ $player->id }}" />
```

### Ejemplo completo

```blade
@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Jugador: {{ $player->name }}</h2>
        </div>

        <livewire:player-notes :playerId="$player->id" />
    </div>
@endsection
```

## 🧪 Ejecutar Tests

```bash
# Ejecutar todos los tests del módulo
php artisan test tests/Feature/PlayerNoteTest.php

# Ejecutar un test específico
php artisan test tests/Feature/PlayerNoteTest.php --filter test_authenticated_user_can_create_player_note

# Ejecutar con coverage
php artisan test tests/Feature/PlayerNoteTest.php --coverage
```

## 📝 Gestión de Permisos

### Asignar permisos a roles

```php
// Asignar permiso a un rol
$role = Role::findByName('staff');
$role->givePermissionTo('create player notes');

// O directamente al usuario
$user->givePermissionTo('create player notes');

// Verificar permisos
if ($user->hasPermissionTo('create player notes')) {
    // Crear nota
}
```

## 🔧 API del Repositorio

### Métodos disponibles

```php
// Obtener todas las notas de un jugador
$notes = $this->playerNoteRepository->getNotesByPlayer($playerId);

// Crear una nueva nota
$note = $this->playerNoteRepository->createNote(
    $playerId,
    $authorId,
    'Contenido de la nota'
);

// Obtener una nota por ID
$note = $this->playerNoteRepository->getNoteById($noteId);

// Eliminar una nota
$deleted = $this->playerNoteRepository->deleteNote($noteId);
```

## 📋 Relaciones del Modelo

```php
// Obtener el jugador de una nota
$player = $note->player;

// Obtener el autor de una nota
$author = $note->author;

// Obtener todas las notas de un jugador
$notes = $player->notes; // Requiere agregar la relación en User model
```

### Agregar relación inversa en User model

```php
public function playerNotes()
{
    return $this->hasMany(PlayerNote::class, 'author_id');
}

public function receivedNotes()
{
    return $this->hasMany(PlayerNote::class, 'player_id');
}
```

## 🎨 Personalización

### Cambiar estilos

La vista usa Tailwind CSS. Para personalizar:

1. Modifica `resources/views/livewire/player-notes.blade.php`
2. Ajusta las clases de Tailwind según tus necesidades
3. O integra con tu sistema de diseño existente

### Agregar más campos

Para agregar más campos a las notas:

1. Crea una migración:

    ```bash
    php artisan make:migration add_fields_to_player_notes_table
    ```

2. Agrega los campos en la migración

3. Actualiza el modelo `PlayerNote.php` en `$fillable`

4. Actualiza la vista `player-notes.blade.php`

### Cambiar las reglas de validación

En `app/Http/Livewire/PlayerNotes.php`:

```php
protected array $rules = [
    'note' => 'required|string|max:1000', // Aumentar a 1000
    'playerId' => 'required|exists:users,id',
];
```

## 🔍 Solución de problemas

### Las notas no se cargan

- Verifica que la migración haya corrido: `php artisan migrate:status`
- Comprueba que el `playerId` exista en la tabla `users`
- Revisa los logs: `storage/logs/laravel.log`

### El componente Livewire no aparece

- Asegúrate de incluir Livewire en tu layout: `@livewireScripts`
- Verifica que el componente esté en `app/Http/Livewire/PlayerNotes.php`
- Ejecuta: `php artisan livewire:discover`

### Permisos no funcionan

- Verifica que Spatie/laravel-permission esté instalado: `composer show spatie/laravel-permission`
- Corre las migraciones del paquete: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"`
- Luego: `php artisan migrate`
- Crea los permisos

### Los tests fallan

- Asegúrate de ejecutar: `php artisan migrate --env=testing`
- Verifica que tengas el factory instalado correctamente
- Ejecuta con flag `--env=testing` si es necesario

## 📚 Recursos adicionales

- [Documentación de Livewire](https://livewire.laravel.com/)
- [Documentación de Spatie Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- [Documentación de Laravel](https://laravel.com/docs)

## 📄 Licencia

Este módulo es parte del proyecto player-notes-module-hiram.

## 👨‍💻 Autor

Desarrollado como módulo completo de Laravel con Livewire siguiendo mejores prácticas.

---

**¿Necesitas ayuda?** Revisa los archivos del módulo o consulta la documentación oficial de Laravel y Livewire.
