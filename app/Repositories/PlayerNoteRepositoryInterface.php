<?php

namespace App\Repositories;

use App\Models\PlayerNote;
use Illuminate\Support\Collection;

interface PlayerNoteRepositoryInterface
{
    /**
     * Get all notes for a specific player.
     *
     * @param int $playerId
     * @return Collection
     */
    public function getNotesByPlayer(int $playerId): Collection;

    /**
     * Create a new note for a player.
     *
     * @param int $playerId
     * @param int $authorId
     * @param string $note
     * @return PlayerNote
     */
    public function createNote(int $playerId, int $authorId, string $note): PlayerNote;

    /**
     * Delete a note by ID.
     *
     * @param int $noteId
     * @return bool
     */
    public function deleteNote(int $noteId): bool;

    /**
     * Get a note by ID.
     *
     * @param int $noteId
     * @return PlayerNote|null
     */
    public function getNoteById(int $noteId): ?PlayerNote;
}
