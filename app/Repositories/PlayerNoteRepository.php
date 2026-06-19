<?php

namespace App\Repositories;

use App\Models\PlayerNote;
use Illuminate\Support\Collection;

class PlayerNoteRepository implements PlayerNoteRepositoryInterface
{
    /**
     * Get all notes for a specific player, ordered by creation date (newest first).
     *
     * @param int $playerId
     * @return Collection
     */
    public function getNotesByPlayer(int $playerId): Collection
    {
        return PlayerNote::query()
            ->where('player_id', $playerId)
            ->with(['author'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new note for a player.
     *
     * @param int $playerId
     * @param int $authorId
     * @param string $note
     * @return PlayerNote
     */
    public function createNote(int $playerId, int $authorId, string $note): PlayerNote
    {
        return PlayerNote::create([
            'player_id' => $playerId,
            'author_id' => $authorId,
            'note' => $note,
        ]);
    }

    /**
     * Delete a note by ID.
     *
     * @param int $noteId
     * @return bool
     */
    public function deleteNote(int $noteId): bool
    {
        $note = PlayerNote::find($noteId);

        if (!$note) {
            return false;
        }

        return $note->delete();
    }

    /**
     * Get a note by ID.
     *
     * @param int $noteId
     * @return PlayerNote|null
     */
    public function getNoteById(int $noteId): ?PlayerNote
    {
        return PlayerNote::find($noteId);
    }
}
