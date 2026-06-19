<div class="player-notes-container">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Notas del Jugador</h3>

        <!-- Notes Table -->
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Autor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nota
                        </th>
                        @canany(['delete', 'update'], App\Models\PlayerNote::class)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($notes as $note)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $note->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $note->author->name ?? 'Usuario desconocido' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-md break-words">
                                {{ $note->note }}
                            </td>
                            @can('delete', $note)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        wire:click="deleteNote({{ $note->id }})"
                                        wire:confirm="¿Estás seguro de que deseas eliminar esta nota?"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay notas registradas para este jugador.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Note Form -->
    @can('create', App\Models\PlayerNote::class)
        <div class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Agregar Nueva Nota</h4>

            <form wire:submit.prevent="saveNote">
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                        Nota <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="note"
                        wire:model="note"
                        rows="4"
                        maxlength="500"
                        placeholder="Escribe la nota aquí (máximo 500 caracteres)..."
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    ></textarea>
                    @error('note')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">
                        {{ strlen($note) }}/500 caracteres
                    </span>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150"
                    >
                        <span wire:loading.remove>Agregar Nota</span>
                        <span wire:loading>
                            <svg class="animate-spin h-4 w-4 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="mt-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <p class="text-sm text-yellow-800">
                No tienes permiso para crear notas. Contacta a un administrador.
            </p>
        </div>
    @endcan

    <!-- Toast notifications (optional with Alpine.js) -->
    @if (session('status'))
        <div
            x-data="{ show: true }"
            x-show="show"
            @note-created.window="show = true; setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg"
        >
            {{ session('status') }}
        </div>
    @endif
</div>
