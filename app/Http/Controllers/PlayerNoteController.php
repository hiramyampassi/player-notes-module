<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class PlayerNoteController extends BaseController
{
    /**
     * Show player notes page.
     *
     * @param User $player
     * @return View
     */
    public function show(User $player): View
    {
        // Opcionalmente, verificar que el usuario tenga permiso de ver las notas
        // $this->authorize('viewAny', PlayerNote::class);

        return view('players.show', [
            'player' => $player,
        ]);
    }

    /**
     * Store a new player note (API endpoint - opcional si prefieres usar Livewire).
     *
     * @return Application
     */
    public function store()
    {
        // Este método puede usarse si prefieres un endpoint API
        // Por ahora, Livewire maneja todo directamente
    }
}
