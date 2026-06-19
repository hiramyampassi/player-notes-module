<?php

/**
 * PLAYER NOTES ROUTES EXAMPLE
 *
 * Agrega estas rutas a tu archivo routes/web.php
 * para que el módulo funcione correctamente.
 */

use App\Http\Controllers\PlayerNoteController;
use Illuminate\Support\Facades\Route;

// Ejemplo de rutas para el módulo de notas de jugador
Route::middleware(['auth'])->group(function () {
    // Mostrar página con notas del jugador
    Route::get('/players/{player}', [PlayerNoteController::class, 'show'])
        ->name('players.show');

    // Alternativa más simple sin controlador
    Route::get('/players/{player}/notes', function (\App\Models\User $player) {
        return view('players.show', ['player' => $player]);
    })->name('players.notes');
});

/**
 * INSTRUCCIONES:
 *
 * 1. Abre routes/web.php
 *
 * 2. Agrega este código:
 *
 *    Route::middleware(['auth'])->group(function () {
 *        Route::get('/players/{player}/notes', function (\App\Models\User $player) {
 *            return view('players.show', ['player' => $player]);
 *        })->name('players.notes');
 *    });
 *
 * 3. O usa el controlador:
 *
 *    Route::middleware(['auth'])->group(function () {
 *        Route::get('/players/{player}', [\App\Http\Controllers\PlayerNoteController::class, 'show'])
 *            ->name('players.show');
 *    });
 *
 * 4. Accede a: http://localhost:8000/players/1/notes
 *    (reemplaza 1 con el ID del jugador/usuario)
 */
