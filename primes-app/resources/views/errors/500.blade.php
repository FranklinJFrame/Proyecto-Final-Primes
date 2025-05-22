@extends('app')
@section('title', 'Error del servidor')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white">
    <h1 class="text-6xl font-bold text-yellow-500 mb-4">500</h1>
    <p class="text-2xl mb-6">Ha ocurrido un error inesperado. Por favor, intenta más tarde.</p>
    <a href="/" class="px-6 py-3 bg-blue-600 rounded-lg text-white font-semibold hover:bg-blue-700 transition">Volver al inicio</a>
</div>
@endsection
