@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Player Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($player->name, 0, 1)) }}</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $player->name }}</h1>
                <p class="text-gray-600">{{ $player->email }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('players.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                ← Volver a Jugadores
            </a>
        </div>
    </div>

    <!-- Player Notes Component -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Notas Internas</h2>
            <p class="text-sm text-gray-600 mt-1">Historial de todas las notas internas registradas para este jugador</p>
        </div>

        <div class="px-6 py-4">
            <livewire:player-notes :playerId="$player->id" />
        </div>
    </div>

    <!-- Additional Information (Optional) -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Total de Notas</h3>
            <p class="text-3xl font-bold text-indigo-600">
                {{ $player->receivedPlayerNotes()->count() ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Última Nota</h3>
            <p class="text-lg text-gray-600">
                @if($player->receivedPlayerNotes()->latest()->first())
                    {{ $player->receivedPlayerNotes()->latest()->first()->created_at->format('d/m/Y H:i') }}
                @else
                    Sin notas
                @endif
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Registrado desde</h3>
            <p class="text-lg text-gray-600">
                {{ $player->created_at->format('d/m/Y') }}
            </p>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estilos adicionales personalizados si es necesario */
    .player-notes-container {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
@endsection
