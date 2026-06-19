<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// =========== PLAYER NOTES ROUTES ===========

Route::middleware(['auth'])->group(function () {
    // Vista de lista de jugadores
    Route::get('/players', function () {
        $players = User::where('id', '!=', auth()->id())->paginate(10);
        return view('players.index', ['players' => $players]);
    })->name('players.index');

    // Vista de detalles del jugador con notas
    Route::get('/players/{player}', function (User $player) {
        $player->load('playerNotes');
        return view('players.show', ['player' => $player]);
    })->name('players.show');

    // API endpoint para obtener notas (opcional, para AJAX)
    Route::get('/api/players/{player}/notes', function (User $player) {
        return response()->json(
            $player->playerNotes()
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    })->name('api.player.notes');
});

// =========== AUTH ROUTES ===========
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Dummy login for testing
    auth()->loginUsingId(1);
    return redirect()->route('players.index');
})->name('login.post');
