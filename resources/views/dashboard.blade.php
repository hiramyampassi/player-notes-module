<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Player Notes</title>
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
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Dashboard
            </a>
            <a href="{{ route('players.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Jugadores
            </a>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-gray-600 mt-1">Bienvenido de vuelta, {{ $currentUser->name }}!</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Total de Usuarios</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Total de Notas</h3>
                <p class="text-3xl font-bold text-green-600">{{ $totalNotes }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-500 mb-2">Notas Recientes</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $recentNotes->count() }}</p>
            </div>
        </div>

        <!-- Notas Recientes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Últimas 5 Notas</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Sobre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Autor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentNotes as $note)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('players.show', $note->player) }}" class="text-indigo-600 hover:text-indigo-800">
                                        {{ $note->player->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $note->author->name }}</td>
                                <td class="px-6 py-4 text-gray-700 max-w-xs truncate">{{ $note->note }}</td>
                                <td class="px-6 py-4 text-gray-600 text-sm">{{ $note->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No hay notas aún. ¡Comienza a crear!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
