<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugadores - Player Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-indigo-600">Player Notes</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Navegación -->
        <div class="mb-6 flex gap-4">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Dashboard
            </a>
            <a href="{{ route('players.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Jugadores
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Jugadores</h2>
            <p class="text-gray-600 mt-1">Selecciona un jugador para ver y gestionar sus notas</p>
        </div>

        <!-- Grid de Jugadores -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($players as $player)
                <a href="{{ route('players.show', $player) }}" class="block">
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-200 overflow-hidden cursor-pointer">
                        <!-- Avatar -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-24 flex items-center justify-center">
                            <span class="text-white text-4xl font-bold">
                                {{ strtoupper(substr($player->name, 0, 1)) }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900">{{ $player->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $player->email }}</p>

                            <!-- Notas count -->
                            <div class="mt-4 p-3 bg-gray-50 rounded">
                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ $player->received_player_notes_count ?? 0 }}
                                </p>
                                <p class="text-gray-600 text-xs">notas internas</p>
                            </div>

                            <!-- Arrow -->
                            <div class="mt-4 flex items-center text-indigo-600">
                                <span class="text-sm font-semibold">Ver Notas</span>
                                <span class="ml-2">→</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">No hay jugadores disponibles</p>
                    <p class="text-gray-500 text-sm mt-1">Se necesitan al menos 2 usuarios en el sistema</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
            <div class="mt-8">
                {{ $players->links() }}
            </div>
        @endif
    </div>
</body>
</html>
