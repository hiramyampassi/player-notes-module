<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Player Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <!-- Logo/Title -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">Player Notes</h1>
                <p class="text-gray-600 mt-2">Sistema de Gestión de Notas</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email (Opcional)
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="staff@example.com o viewer@example.com"
                    >
                    <p class="text-xs text-gray-500 mt-1">Deja vacío para usar staff@example.com</p>
                </div>

                <div>
                    <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded border border-amber-200">
                        <strong>Usuarios disponibles:</strong><br>
                        📧 <code class="font-mono text-xs">staff@example.com</code> - Con permisos ✅<br>
                        📧 <code class="font-mono text-xs">viewer@example.com</code> - Sin permisos ❌
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-200"
                >
                    Iniciar Sesión
                </button>
            </form>

            <!-- Info -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Demo:</strong> El login es automático. Solo haz click en "Iniciar Sesión" para acceder con el primer usuario.
                </p>
            </div>

            <!-- Features -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-4">Características:</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <span class="text-indigo-600">✓</span> Crear notas para jugadores
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-indigo-600">✓</span> Ver historial completo
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-indigo-600">✓</span> Eliminar notas
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-indigo-600">✓</span> Interfaz reactiva (Livewire)
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white text-sm mt-6">
            Player Notes Module v1.0
        </p>
    </div>
</body>
</html>
