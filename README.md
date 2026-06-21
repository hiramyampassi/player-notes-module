# 🎯 Módulo Player Notes - Laravel + Livewire

Sistema completo de gestión de notas internas para jugadores usando Laravel 13, Livewire 4 y Spatie Permission.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Instalación Rápida](#instalación-rápida)
- [Rutas Disponibles](#rutas-disponibles)
- [Uso del Módulo](#uso-del-módulo)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Administración de Permisos](#administración-de-permisos)
- [Tests](#tests)
- [Troubleshooting](#troubleshooting)

---

## ✨ Características

✅ **Crear notas** internas sobre jugadores específicos  
✅ **Ver historial** de notas ordenadas por fecha (más recientes primero)  
✅ **Eliminar notas** con confirmación de acción  
✅ **Validación completa**: requerido, máximo 500 caracteres  
✅ **Sistema de permisos**: basado en roles con Spatie/laravel-permission  
✅ **Componente Livewire**: reactivo, sin recargas de página  
✅ **Patrón Repositorio**: lógica de datos separada y reutilizable  
✅ **Dashboard**: visualización de estadísticas  
✅ **API JSON**: endpoint para obtener notas  
✅ **Tests automatizados**: 9 casos de prueba cubriendo toda la funcionalidad

---

## 🚀 Instalación Rápida

### Requisitos

- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js (opcional, para compilar assets)

### Pasos

**1. Ubicarse en el proyecto**

```bash
cd d:\Projects\player-notes-module-hiram
```

**2. Instalar dependencias**

```bash
composer install
npm install
```

**3. Configurar `.env`** (ya está configurado)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=player_notes_db
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generar clave**

```bash
php artisan key:generate
```

**5. Crear tablas, permisos y datos de prueba**

```bash
php artisan migrate:fresh --seed
```

**✅ Se crearán automáticamente:**

- ✅ Tablas de base de datos
- ✅ Permisos y roles
- ✅ 2 usuarios de prueba con diferentes permisos
- ✅ 8 jugadores de prueba

**6. Iniciar servidor**

```bash
php artisan serve
```

✅ **¡Listo!** Acceder a: http://localhost:8000

---

## 🌐 Rutas Disponibles

### Rutas Públicas

| Método | Ruta | Descripción      |
| ------ | ---- | ---------------- |
| GET    | `/`  | Página de inicio |

### Rutas Autenticadas

| Método | Ruta                      | Descripción                 |
| ------ | ------------------------- | --------------------------- |
| GET    | `/login`                  | Página login                |
| POST   | `/login`                  | Iniciar sesión (auto login) |
| POST   | `/logout`                 | Cerrar sesión               |
| GET    | `/dashboard`              | Dashboard con estadísticas  |
| GET    | `/players`                | Lista de jugadores          |
| GET    | `/players/{id}`           | Perfil + notas (Livewire)   |
| GET    | `/api/players/{id}/notes` | API JSON notas              |

### URLs de Ejemplo

```
http://localhost:8000                   # Home
http://localhost:8000/login             # Login
http://localhost:8000/dashboard         # Dashboard
http://localhost:8000/players           # Jugadores
http://localhost:8000/players/2         # Perfil + Notas
http://localhost:8000/api/players/2/notes # API
```

---

## 📝 Uso del Módulo (Paso a Paso)

### 1. Iniciar Sesión

**Dos usuarios de prueba disponibles:**

| Email                | Rol   | Permiso | Verá Botón |
| -------------------- | ----- | ------- | ---------- |
| `staff@example.com`  | staff | ✅ SÍ   | ✅ SÍ      |
| `viewer@example.com` | —     | ❌ NO   | ❌ NO      |

**Opción A: Usar Staff (por defecto)**

```
1. Ir a http://localhost:8000/login
2. Dejar el campo email VACÍO
3. Click en "Iniciar Sesión"
4. Se loguea como: staff@example.com ✅ (CON PERMISOS)
```

**Opción B: Cambiar a Viewer (SIN permisos)**

```
1. Ir a http://localhost:8000/login
2. Escribir en el campo: viewer@example.com
3. Click en "Iniciar Sesión"
4. Se loguea como: viewer@example.com ❌ (SIN PERMISOS)
```

**Opción C: Cambiar de nuevo a Staff**

```
1. Cerrar sesión (botón arriba a la derecha)
2. Ir a http://localhost:8000/login
3. Escribir: staff@example.com
4. Click en "Iniciar Sesión"
```

### 2. Ver Lista de Jugadores

```
1. Click en "Jugadores" en la navegación
2. O ir directamente a http://localhost:8000/players
3. Verás una lista con paginación de jugadores
```

### 3. Ver Notas de un Jugador

```
1. Click en un jugador de la lista
2. O ir a http://localhost:8000/players/2
3. Se abre la vista con el componente Livewire
4. Verás:
   - Datos del jugador
   - Tabla con historial de notas
   - Formulario para agregar notas
   - Estadísticas
```

### 4. Crear una Nota

```
1. En la sección "Agregar Nueva Nota"
2. Escribe en el textarea
3. Máximo 500 caracteres (contador automático)
4. Click en "Agregar Nota"
5. ¡La nota aparece al instante! (Livewire sin recargas)
```

### 5. Eliminar una Nota

```
1. Click en botón "Eliminar" de la nota
2. Confirma en el diálogo
3. Nota eliminada automáticamente
```

---

## 🏗️ Estructura del Proyecto

### Código Principal

```
app/
├── Models/
│   ├── PlayerNote.php              # Modelo nota (relaciones)
│   └── User.php                    # Modelo usuario (Spatie)
├── Http/Livewire/
│   └── PlayerNotes.php             # Componente reactivo
├── Repositories/
│   ├── PlayerNoteRepositoryInterface.php
│   └── PlayerNoteRepository.php    # Acceso datos
├── Policies/
│   └── PlayerNotePolicy.php        # Autorización
└── Providers/
    └── AppServiceProvider.php      # Inyección servicios
```

### Base de Datos

```
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_player_notes_table.php
│   └── create_permission_tables.php
├── factories/
│   └── PlayerNoteFactory.php       # Datos para tests
└── seeders/
    ├── PermissionSeeder.php        # Permisos y roles
    └── DatabaseSeeder.php          # Datos iniciales
```

### Vistas y Assets

```
resources/
├── views/
│   ├── livewire/player-notes.blade.php
│   ├── players/
│   │   ├── index.blade.php         # Lista jugadores
│   │   └── show.blade.php          # Perfil jugador
│   ├── dashboard.blade.php
│   └── auth/login.blade.php
└── js/app.js                       # Assets
```

### Testing

```
tests/
├── TestCase.php                    # Setup tests
└── Feature/
    └── PlayerNoteTest.php          # 9 tests
```

---

## 🔐 Administración de Permisos

### Usuarios de Prueba Pre-configurados

Al ejecutar `php artisan migrate:fresh --seed`, se crean automáticamente:

| Usuario     | Email                | Rol   | Permisos                | Uso                 |
| ----------- | -------------------- | ----- | ----------------------- | ------------------- |
| Staff User  | `staff@example.com`  | staff | ✅ create, delete, view | Prueba completa     |
| Viewer User | `viewer@example.com` | —     | ❌ Ninguno              | Prueba sin permisos |

### Prueba Visual: Diferencia de Permisos

**Con Staff User (staff@example.com):**

```
✅ VES "Agregar Nueva Nota"
✅ VES textarea
✅ VES botón "Agregar Nota"
✅ PUEDES crear notas
✅ PUEDES eliminar notas
```

**Con Viewer User (viewer@example.com):**

```
❌ NO VES "Agregar Nueva Nota"
❌ VES mensaje: "No tienes permiso para crear notas"
❌ NO PUEDES crear notas
✅ PUEDES ver las notas existentes
```

### Permisos Disponibles

| Permiso               | Descripción           |
| --------------------- | --------------------- |
| `create player notes` | Crear nuevas notas    |
| `delete player notes` | Eliminar notas        |
| `edit player notes`   | Editar notas (futuro) |
| `view player notes`   | Ver notas             |

### Roles Predefinidos

| Rol     | Permisos             |
| ------- | -------------------- |
| `admin` | Todos                |
| `staff` | create, delete, view |

### Modificar Permisos Manualmente (Tinker)

```bash
php artisan tinker
```

```php
# Obtener usuario
>>> $user = User::find(1);

# Dar permiso específico
>>> $user->givePermissionTo('create player notes');

# Quitar permiso
>>> $user->revokePermissionTo('create player notes');

# Verificar permiso
>>> $user->hasPermissionTo('create player notes')
true

# Ver todos los permisos
>>> $user->getAllPermissions();

# Asignar rol completo
>>> $user->assignRole('staff');

# Salir
>>> exit
```

---

## 🎬 Demo Rápida: Probar Ambos Usuarios

### Inicio Rápido

```bash
php artisan migrate:fresh --seed
php artisan serve
```

### Escenario 1: Usuario CON Permisos ✅ (Staff)

```
1. Ir a: http://localhost:8000/login
2. Campo email: DEJAR VACÍO (por defecto staff)
3. Click "Iniciar Sesión"
4. Click en "Jugadores"
5. Selecciona cualquier jugador
6. ✅ VES: formulario "Agregar Nueva Nota"
7. ✅ PUEDES: crear notas automáticamente
8. ✅ PUEDES: eliminar notas con confirmación
```

### Escenario 2: Usuario SIN Permisos ❌ (Viewer)

```
1. Cerrar sesión (icono usuario arriba a la derecha)
2. Ir a: http://localhost:8000/login
3. Campo email: escribir viewer@example.com
4. Click "Iniciar Sesión"
5. Click en "Jugadores"
6. Selecciona el MISMO jugador
7. ❌ NO VES: formulario "Agregar Nueva Nota"
8. ✅ VES: mensaje "No tienes permiso para crear notas"
9. ✅ VES: todas las notas creadas por otros
```

### Comparación en Tiempo Real (2 Ventanas)

```
Ventana 1: Staff (staff@example.com)
- Email: dejar vacío
- ✅ Puede crear notas
- ✅ Puede eliminar notas

Ventana 2: Viewer (viewer@example.com)
- Email: viewer@example.com
- ❌ No ve botón para crear
- ✅ Solo ve notas existentes

→ AMBAS ven el mismo jugador con las mismas notas
→ LA DIFERENCIA: quién puede modificar
```

---

## 🧪 Tests

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Solo Player Notes
php artisan test tests/Feature/PlayerNoteTest.php

# Un test específico
php artisan test tests/Feature/PlayerNoteTest.php --filter test_player_note_is_saved_correctly

# Con cobertura
php artisan test --coverage
```

### Tests Incluidos (9 en total)

✅ Guardar nota en base de datos  
✅ Crear nota con repositorio  
✅ Validación nota vacía  
✅ Validación máximo 500 caracteres  
✅ Obtener notas por jugador  
✅ Eliminar nota  
✅ Relaciones del modelo  
✅ Asignar permisos a usuario  
✅ Ordenamiento por fecha

---

## 📊 API Endpoint

### Obtener Notas en JSON

```bash
GET /api/players/2/notes
```

**Respuesta:**

```json
[
    {
        "id": 1,
        "player_id": 2,
        "author_id": 1,
        "note": "Excelente rendimiento",
        "created_at": "2026-06-20T10:30:00",
        "author": {
            "id": 1,
            "name": "Test User"
        }
    }
]
```

---

## 🔧 Comandos Útiles

### Database

```bash
php artisan migrate              # Crear tablas
php artisan migrate:fresh        # Reset total
php artisan migrate:fresh --seed # Reset + datos
php artisan migrate:status       # Ver estado
```

### Seeders

```bash
php artisan db:seed                        # Todos
php artisan db:seed --class=PermissionSeeder # Específico
php artisan migrate:fresh --seed           # Con migraciones
```

### Tinker (Terminal PHP)

```bash
php artisan tinker
>>> User::all()
>>> \App\Models\PlayerNote::all()
>>> exit
```

### Cache y Config

```bash
php artisan cache:clear
php artisan config:clear
php artisan key:generate
```

### Testing

```bash
php artisan test
php artisan test --coverage
php artisan test --parallel
```

---

## 💡 Ejemplos de Código

### Usar el Repositorio

```php
use App\Repositories\PlayerNoteRepositoryInterface;

class NotesService
{
    public function __construct(
        private PlayerNoteRepositoryInterface $notes
    ) {}

    public function getPlayerNotes($playerId)
    {
        return $this->notes->getNotesByPlayer($playerId);
    }

    public function addNote($playerId, $content)
    {
        return $this->notes->createNote(
            $playerId,
            auth()->id(),
            $content
        );
    }
}
```

### En Blade

```blade
<!-- Componente Livewire -->
<livewire:player-notes :playerId="$player->id" />

<!-- Contar notas -->
{{ $player->receivedPlayerNotes()->count() }} notas

<!-- Verificar permisos -->
@can('create player notes')
    <button>Agregar</button>
@endcan
```

### En Modelo

```php
$user = User::find(1);

$user->playerNotes;              // Notas que creó
$user->receivedPlayerNotes;      // Notas sobre él
$user->hasPermissionTo('create player notes');
```

---

## 🐛 Troubleshooting

### "Key was too long" en migraciones

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

### "No permission named `create player notes`"

```bash
php artisan db:seed --class=PermissionSeeder
```

### "Call to undefined method givePermissionTo()"

- Verificar que `User.php` tenga: `use Spatie\Permission\Traits\HasRoles;`
- Verificar que use el trait: `use HasRoles;`

### Livewire no funciona

- Verificar `@livewireScripts` en layout
- Clear cache: `php artisan cache:clear`

### Tests fallan

```bash
php artisan migrate --env=testing
php artisan test
```

### "Table doesn't exist"

```bash
php artisan migrate:fresh --seed
```

---

## 📦 Stack Tecnológico

| Componente        | Versión |
| ----------------- | ------- |
| PHP               | 8.3+    |
| Laravel           | 13.8+   |
| Livewire          | 4.3+    |
| MySQL             | 8.0+    |
| Spatie Permission | 8.0+    |
| Tailwind CSS      | Latest  |

---

## 🔄 Flujo de Datos

```
Usuario abre /players/2
    ↓
Carga view players.show
    ↓
Componente Livewire PlayerNotes
    ↓
mount() → Repositorio getNotesByPlayer()
    ↓
Muestra tabla de notas
    ↓
Usuario escribe + click
    ↓
saveNote() → Validación → Autorización → Repositorio createNote()
    ↓
Livewire refresca (sin recargar página)
    ↓
Tabla actualizada ✅
```

---

## 🎯 Próximas Mejoras (Opcional)

- Editar notas existentes
- Búsqueda y filtros avanzados
- Exportar notas a PDF
- Notificaciones en tiempo real
- Adjuntar archivos
- Historial de cambios
- Tags o categorías
- Menciones (@usuario)

---

## 🆘 Soporte Rápido

| Problema              | Solución                                       |
| --------------------- | ---------------------------------------------- |
| No logra entrar       | `php artisan db:seed` (crear usuarios)         |
| Permisos no funcionan | `php artisan db:seed --class=PermissionSeeder` |
| Tabla no existe       | `php artisan migrate:fresh --seed`             |
| Livewire no carga     | Limpiar cache: `php artisan cache:clear`       |
| Tests fallan          | `php artisan test -v` para ver detalles        |

---

## 📄 Licencia

Proyecto player-notes-module-hiram

---

## 🚀 ¡Listo para Usar!

```bash
# 1. Instalar dependencias
composer install && npm install

# 2. Configurar BD (ya está en .env)
# DB_DATABASE=player_notes_db

# 3. Crear todo
php artisan migrate:fresh --seed

# 4. Iniciar servidor
php artisan serve

# 5. Abrir navegador
# http://localhost:8000
```

**¡El módulo Player Notes está 100% funcional!** 🎉

Para más información, consulta el código en `app/` y `resources/views/`

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
