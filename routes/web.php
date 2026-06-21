<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// =========== HOME ===========
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// =========== AUTHENTICATION ROUTES ===========
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $email = $request->input('email');

        // If email is provided, find that specific user
        if (!empty($email)) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect()->back()->withErrors('Usuario no encontrado: ' . $email);
            }
        } else {
            // If no email provided, default to first user (staff)
            $user = User::first();
            if (!$user) {
                return redirect()->back()->withErrors('No hay usuarios en la base de datos');
            }
        }

        auth()->loginUsingId($user->id);
        return redirect()->route('dashboard')->with('success', 'Loguado como: ' . $user->email);
    })->name('login.post');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('home')->with('success', 'Sesión cerrada');
})->name('logout');

// =========== AUTHENTICATED ROUTES ===========
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $totalUsers = User::count();
        $totalNotes = \App\Models\PlayerNote::count();
        $recentNotes = \App\Models\PlayerNote::with(['player', 'author'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('dashboard', [
            'totalUsers' => $totalUsers,
            'totalNotes' => $totalNotes,
            'recentNotes' => $recentNotes,
            'currentUser' => auth()->user(),
        ]);
    })->name('dashboard');

    // Lista de jugadores
    Route::get('/players', function () {
        $players = User::where('id', '!=', auth()->id())
            ->withCount('receivedPlayerNotes')
            ->paginate(12);
        return view('players.index', ['players' => $players]);
    })->name('players.index');

    // Detalles del jugador con componente Livewire
    Route::get('/players/{player}', function (User $player) {
        if ($player->id === auth()->id()) {
            return redirect()->route('players.index')->withErrors('No puedes ver tus propias notas aquí');
        }
        return view('players.show', ['player' => $player]);
    })->name('players.show');

    // API endpoint para obtener notas (opcional, para AJAX)
    Route::get('/api/players/{player}/notes', function (User $player) {
        return response()->json(
            $player->receivedPlayerNotes()
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    })->name('api.player.notes');
});
