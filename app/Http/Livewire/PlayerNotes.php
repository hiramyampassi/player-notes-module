<?php

namespace App\Http\Livewire;

use App\Models\PlayerNote;
use App\Repositories\PlayerNoteRepositoryInterface;
use Illuminate\Support\Collection;
use Livewire\Component;

class PlayerNotes extends Component
{
    /**
     * The player ID for which notes are being displayed/created.
     */
    public int $playerId;

    /**
     * The note content being entered.
     */
    public string $note = '';

    /**
     * Collection of notes for the player.
     */
    public Collection $notes;

    /**
     * Repository instance for player notes.
     */
    protected PlayerNoteRepositoryInterface $playerNoteRepository;

    /**
     * Validation rules for the form.
     */
    protected array $rules = [
        'note' => 'required|string|max:500',
        'playerId' => 'required|exists:users,id',
    ];

    /**
     * Messages for validation errors.
     */
    protected array $messages = [
        'note.required' => 'La nota es obligatoria.',
        'note.max' => 'La nota no puede exceder 500 caracteres.',
        'playerId.required' => 'El ID del jugador es obligatorio.',
        'playerId.exists' => 'El jugador especificado no existe.',
    ];

    /**
     * Constructor to inject the repository.
     */
    public function __construct()
    {
        parent::__construct();
        $this->playerNoteRepository = app(PlayerNoteRepositoryInterface::class);
    }

    /**
     * Mount the component with the player ID.
     */
    public function mount(int $playerId): void
    {
        $this->playerId = $playerId;
        $this->loadNotes();
    }

    /**
     * Load notes for the current player.
     */
    public function loadNotes(): void
    {
        $this->notes = $this->playerNoteRepository->getNotesByPlayer($this->playerId);
    }

    /**
     * Save a new note for the player.
     */
    public function saveNote(): void
    {
        // Authorize the action
        $this->authorize('create', PlayerNote::class);

        // Validate the input
        $this->validate();

        // Create the note through the repository
        $this->playerNoteRepository->createNote(
            $this->playerId,
            auth()->id(),
            $this->note
        );

        // Reset the note field
        $this->reset('note');

        // Reload the notes
        $this->loadNotes();

        // Dispatch a success event (optional)
        $this->dispatch('note-created', message: 'Nota creada exitosamente.');
    }

    /**
     * Delete a note.
     */
    public function deleteNote(int $noteId): void
    {
        $note = $this->playerNoteRepository->getNoteById($noteId);

        if (!$note) {
            return;
        }

        // Authorize the action
        $this->authorize('delete', $note);

        // Delete the note
        $this->playerNoteRepository->deleteNote($noteId);

        // Reload the notes
        $this->loadNotes();

        // Dispatch a success event (optional)
        $this->dispatch('note-deleted', message: 'Nota eliminada exitosamente.');
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.player-notes');
    }
}
