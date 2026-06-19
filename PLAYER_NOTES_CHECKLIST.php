#!/usr/bin/env php
<?php

/**
 * CHECKLIST DE VERIFICACIÓN - Módulo Player Notes
 * 
 * Ejecuta este script para verificar que todos los archivos
 * del módulo han sido copiados correctamente.
 * 
 * Uso: php artisan tinker < player-notes-checklist.php
 * O simplemente verifica manualmente usando esta lista
 */

$requiredFiles = [
    // Modelos
    'app/Models/PlayerNote.php',
    
    // Repositorios
    'app/Repositories/PlayerNoteRepositoryInterface.php',
    'app/Repositories/PlayerNoteRepository.php',
    
    // Componentes Livewire
    'app/Http/Livewire/PlayerNotes.php',
    
    // Políticas
    'app/Policies/PlayerNotePolicy.php',
    
    // Controladores
    'app/Http/Controllers/PlayerNoteController.php',
    
    // Migraciones
    'database/migrations/2026_06_19_000000_create_player_notes_table.php',
    
    // Factories
    'database/factories/PlayerNoteFactory.php',
    
    // Seeders
    'database/seeders/PermissionSeeder.php',
    
    // Vistas
    'resources/views/livewire/player-notes.blade.php',
    'resources/views/players/show.blade.php',
    
    // Tests
    'tests/Feature/PlayerNoteTest.php',
    
    // Documentación
    'PLAYER_NOTES_README.md',
    'PLAYER_NOTES_SETUP.md',
    'PLAYER_NOTES_ROUTES_EXAMPLE.php',
    'PLAYER_NOTES_SUMMARY.md',
];

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✓ CHECKLIST DE VERIFICACIÓN - MÓDULO PLAYER NOTES\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$basePath = base_path();
$missingFiles = [];
$foundFiles = [];

foreach ($requiredFiles as $file) {
    $fullPath = $basePath . DIRECTORY_SEPARATOR . $file;
    
    if (file_exists($fullPath)) {
        $foundFiles[] = $file;
        echo "  ✓ {$file}\n";
    } else {
        $missingFiles[] = $file;
        echo "  ✗ {$file} (NO ENCONTRADO)\n";
    }
}

echo "\n";
echo "───────────────────────────────────────────────────────────────\n";
echo "  Resumen:\n";
echo "  ✓ Archivos encontrados: " . count($foundFiles) . "/" . count($requiredFiles) . "\n";

if (count($missingFiles) > 0) {
    echo "  ✗ Archivos faltantes: " . count($missingFiles) . "\n";
} else {
    echo "  ✓ ¡Todos los archivos están presentes!\n";
}

echo "───────────────────────────────────────────────────────────────\n\n";

if (count($missingFiles) > 0) {
    echo "  ⚠ Archivos faltantes a copiar:\n";
    foreach ($missingFiles as $file) {
        echo "    - {$file}\n";
    }
    echo "\n";
}

// Verificar configuración
echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✓ VERIFICACIÓN DE CONFIGURACIÓN\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Verificar si AppServiceProvider tiene el repositorio
$appServiceProvider = file_get_contents(app_path('Providers/AppServiceProvider.php'));
if (strpos($appServiceProvider, 'PlayerNoteRepository') !== false) {
    echo "  ✓ Repositorio registrado en AppServiceProvider\n";
} else {
    echo "  ✗ Repositorio NO registrado en AppServiceProvider\n";
    echo "    Agrega lo siguiente en app/Providers/AppServiceProvider.php:\n";
    echo "    \$this->app->bind(PlayerNoteRepositoryInterface::class, PlayerNoteRepository::class);\n";
}

// Verificar AuthServiceProvider
if (file_exists(app_path('Providers/AuthServiceProvider.php'))) {
    $authProvider = file_get_contents(app_path('Providers/AuthServiceProvider.php'));
    if (strpos($authProvider, 'PlayerNotePolicy') !== false) {
        echo "  ✓ Política registrada en AuthServiceProvider\n";
    } else {
        echo "  ⚠ Política NO registrada en AuthServiceProvider\n";
        echo "    Necesitas agregar: \n";
        echo "    PlayerNote::class => PlayerNotePolicy::class,\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✓ PRÓXIMOS PASOS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$nextSteps = [
    "1. Ejecutar migraciones:" => "php artisan migrate",
    "2. Crear permisos:" => "php artisan db:seed --class=PermissionSeeder",
    "3. Registrar política en AuthServiceProvider.php" => "Ver instrucciones arriba",
    "4. Agregar rutas en routes/web.php" => "Ver PLAYER_NOTES_ROUTES_EXAMPLE.php",
    "5. Ejecutar tests:" => "php artisan test tests/Feature/PlayerNoteTest.php",
    "6. Acceder al módulo:" => "http://localhost:8000/players/{id}/notes",
];

foreach ($nextSteps as $step => $command) {
    echo "  {$step}\n";
    echo "     {$command}\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✓ ¡El módulo está listo para usar!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
