# 🎯 COMANDOS ÚTILES - Módulo Player Notes

Aquí está una referencia rápida de los comandos más importantes para trabajar con el módulo.

## 🚀 Instalación

```bash
# Instalar Livewire y Spatie Permission
composer require livewire/livewire spatie/laravel-permission

# Ejecutar todas las migraciones
php artisan migrate

# Crear permisos y roles
php artisan db:seed --class=PermissionSeeder

# Limpiar cache de configuración
php artisan cache:clear && php artisan config:clear
```

## 🧪 Pruebas

```bash
# Ejecutar todos los tests del módulo
php artisan test tests/Feature/PlayerNoteTest.php

# Ejecutar un test específico
php artisan test tests/Feature/PlayerNoteTest.php --filter test_authenticated_user_can_create_player_note

# Ejecutar con output verboso
php artisan test tests/Feature/PlayerNoteTest.php -v

# Ejecutar con coverage
php artisan test tests/Feature/PlayerNoteTest.php --coverage

# Ejecutar tests con fail-fast (para al primer error)
php artisan test tests/Feature/PlayerNoteTest.php --stop-on-failure
```

## 💾 Base de Datos

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ejecutar migraciones específicas
php artisan migrate --path=database/migrations/2026_06_19_000000_create_player_notes_table.php

# Revertir última migración
php artisan migrate:rollback

# Revertir todas las migraciones
php artisan migrate:reset

# Revertir y re-ejecutar
php artisan migrate:refresh

# Limpiar la base de datos completamente
php artisan migrate:fresh --seed

# Ejecutar seeder específica
php artisan db:seed --class=PermissionSeeder
```

## 🔐 Permisos y Roles (Tinker)

```bash
php artisan tinker
```

```php
// Ver todos los permisos
>>> Spatie\Permission\Models\Permission::all();

// Ver todos los roles
>>> Spatie\Permission\Models\Role::all();

// Crear un permiso
>>> Spatie\Permission\Models\Permission::create(['name' => 'edit player notes']);

// Crear un rol
>>> $role = Spatie\Permission\Models\Role::create(['name' => 'moderator']);

// Asignar permiso a rol
>>> $role->givePermissionTo('create player notes');

// Asignar permiso a usuario
>>> $user = App\Models\User::find(1);
>>> $user->givePermissionTo('create player notes');

// Verificar si usuario tiene permiso
>>> $user->hasPermissionTo('create player notes');

// Asignar rol a usuario
>>> $user->assignRole('staff');

// Quitar rol
>>> $user->removeRole('staff');

// Quitar todos los permisos
>>> $user->revokePermissionTo('create player notes');
```

## 🔄 Livewire

```bash
# Descubrir componentes Livewire
php artisan livewire:discover

# Crear nuevo componente Livewire
php artisan make:livewire ComponentName

# Listar componentes
php artisan livewire:list
```

## 📊 Base de Datos (Verificación)

```bash
php artisan tinker
```

```php
// Ver tabla player_notes
>>> DB::table('player_notes')->get();

// Contar notas
>>> DB::table('player_notes')->count();

// Ver notas de un jugador específico
>>> DB::table('player_notes')->where('player_id', 1)->get();

// Ver últimas 5 notas
>>> DB::table('player_notes')->latest()->limit(5)->get();

// Eliminar todas las notas (en desarrollo)
>>> DB::table('player_notes')->truncate();

// Ver estructura de tabla
>>> DB::connection()->getSchemaBuilder()->getColumnListing('player_notes');
```

## 🗑️ Limpiar/Reset

```bash
# Limpiar la aplicación completamente (desarrollo)
php artisan tinker
>>> Illuminate\Support\Facades\DB::table('player_notes')->truncate();

# O usar migrations
php artisan migrate:fresh --seed

# Borrar archivo de log
php artisan tinker
>>> File::delete(storage_path('logs/laravel.log'));
```

## 📝 Logging

```bash
# Ver últimas líneas del log
tail -f storage/logs/laravel.log

# En Windows (PowerShell)
Get-Content storage/logs/laravel.log -Tail 50 -Wait

# Borrar logs
php artisan tinker
>>> File::put(storage_path('logs/laravel.log'), '');
```

## 🎨 Assets y Views

```bash
# Compilar assets (Tailwind, etc.)
npm run build

# Watch mode para desarrollo
npm run dev

# Publicar archivos de paquetes
php artisan vendor:publish --provider="Livewire\LivewireServiceProvider" --asset=public
```

## 🔍 Debugging

```bash
# Ver variables dumped
php artisan tinker
>>> \Log::info('Debug info', ['key' => 'value']);

# Verificar clase existe
>>> class_exists('App\\Models\\PlayerNote');

# Ver rutas
php artisan route:list --name=players

# Ver eventos
php artisan event:list
```

## 📦 Composer

```bash
# Ver versiones instaladas
composer show livewire/livewire
composer show spatie/laravel-permission

# Actualizar paquetes
composer update

# Autoload dumping
composer dump-autoload -o
```

## 🚢 Producción

```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar cache en producción
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver configuración
php artisan config:show APP_ENV
```

## 🆘 Troubleshooting

```bash
# Verificar que todo esté correcto
php artisan doctor

# Ver errores
php artisan config:cache --force

# Regenerar optimized files
php artisan optimize

# Limpiar todo y reiniciar
php artisan optimize:clear

# Verificar integridad del proyecto
php artisan vendor:publish --force
```

## 📋 Verificación Rápida

```bash
# ¿Está la migración creada?
php artisan migrate:status | grep player_notes

# ¿Está el modelo?
php artisan tinker
>>> App\Models\PlayerNote::count();

# ¿Está el componente Livewire?
php artisan livewire:list | grep PlayerNotes

# ¿Están los permisos?
php artisan tinker
>>> Spatie\Permission\Models\Permission::where('name', 'like', '%player%')->get();

# ¿Están los tests?
php artisan test --list | grep PlayerNote
```

## 🔗 URLs útiles

```
Inicio: http://localhost:8000
Notas de jugador: http://localhost:8000/players/{id}/notes
PHPMyAdmin (si instalado): http://localhost/phpmyadmin
Laravel Debugbar: Se activa automáticamente en desarrollo
```

## 📚 Referencia Rápida

| Tarea    | Comando                                               |
| -------- | ----------------------------------------------------- |
| Migrar   | `php artisan migrate`                                 |
| Seed     | `php artisan db:seed --class=PermissionSeeder`        |
| Rollback | `php artisan migrate:rollback`                        |
| Fresh    | `php artisan migrate:fresh --seed`                    |
| Tests    | `php artisan test tests/Feature/PlayerNoteTest.php`   |
| Tinker   | `php artisan tinker`                                  |
| Build    | `npm run build`                                       |
| Dev      | `npm run dev`                                         |
| Clear    | `php artisan cache:clear && php artisan config:clear` |

---

**¡Guarda este archivo para referencia rápida!** 📌
