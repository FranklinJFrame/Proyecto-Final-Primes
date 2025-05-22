@extends('app')
@section('title', 'Acceso denegado')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white">
    <h1 class="text-6xl font-bold text-red-500 mb-4">403</h1>
    <p class="text-2xl mb-6">No tienes permiso para acceder a esta página</p>
    <a href="/" class="px-6 py-3 bg-blue-600 rounded-lg text-white font-semibold hover:bg-blue-700 transition">Volver al inicio</a>
</div>
@endsection
