<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player->name }} - Player Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
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
            <a href="{{ route('players.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Jugadores
            </a>
        </div>

        <!-- Player Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($player->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $player->name }}</h1>
                    <p class="text-gray-600 text-lg">{{ $player->email }}</p>
                </div>
            </div>

            <a href="{{ route('players.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                ← Volver a Jugadores
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Total de Notas</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ $player->receivedPlayerNotes()->count() ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Última Nota</h3>
                <p class="text-lg text-gray-600">
                    @if($player->receivedPlayerNotes()->latest()->first())
                        {{ $player->receivedPlayerNotes()->latest()->first()->created_at->format('d/m/Y H:i') }}
                    @else
                        Sin notas aún
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Miembro desde</h3>
                <p class="text-lg text-gray-600">{{ $player->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Player Notes Component -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Notas Internas</h2>
                <p class="text-sm text-gray-600 mt-1">Gestiona todas las notas internas registradas para este jugador</p>
            </div>

            <div class="px-6 py-4">
                @can('create player notes')
                    <livewire:player-notes :playerId="$player->id" />
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            No tienes permisos para crear notas. Contacta al administrador.
                        </p>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
