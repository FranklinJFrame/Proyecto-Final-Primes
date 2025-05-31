@php
    $user = auth()->user();
@endphp

<div class="min-h-screen bg-black text-white flex flex-col items-center py-16">
    @if (session('message'))
        <div class="w-full max-w-2xl mb-4">
            <div class="bg-green-500 text-white p-4 rounded-lg">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div class="w-full max-w-2xl bg-gray-900 rounded-2xl shadow-2xl p-8 border border-blue-500/20">
        <h2 class="text-3xl font-bold mb-8 text-center cyber-glitch-text">Mi Cuenta</h2>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Datos Básicos -->
            <div class="flex-1">
                <h3 class="text-xl font-semibold mb-4 text-blue-400">Datos Personales</h3>
                <ul class="space-y-2">
                    <li><span class="font-bold">Nombre:</span> {{ $user->name ?? 'No definido' }}</li>
                    <li><span class="font-bold">Email:</span> {{ $user->email ?? 'No definido' }}</li>
                    <li><span class="font-bold">Teléfono:</span> {{ $user->telefono ?? 'No definido' }}</li>
                    <li><span class="font-bold">Fecha de registro:</span> {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'No definido' }}</li>
                </ul>
            </div>
            <!-- Últimos Pedidos -->
            <div class="flex-1">
                <h3 class="text-xl font-semibold mb-4 text-blue-400">Últimos Pedidos</h3>
                @php
                    $ultimosPedidos = $user->pedidos()->latest()->take(3)->get();
                @endphp
                @if($ultimosPedidos->count())
                    <ul class="space-y-2">
                        @foreach($ultimosPedidos as $pedido)
                            <li class="border-b border-blue-500/10 pb-2 mb-2">
                                <span class="font-bold">#{{ $pedido->id }}</span> - {{ $pedido->created_at->format('d/m/Y') }}<br>
                                <span class="text-sm text-gray-400">Total: RD$ {{ number_format($pedido->total, 2) }}</span>
                                <a href="/mis-pedidos/{{ $pedido->id }}" class="ml-2 text-blue-400 hover:underline">Ver detalles</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400">No tienes pedidos recientes.</p>
                @endif
            </div>
        </div>
        <!-- Dirección principal -->
        <div class="mt-10">
            <h3 class="text-xl font-semibold mb-4 text-blue-400">Dirección Principal</h3>
            @php
                $direccion = $user->direccions()->first();
            @endphp
            @if($direccion)
                <div class="bg-gray-800 rounded-lg p-4 border border-blue-500/10">
                    <div><span class="font-bold">Dirección:</span> {{ $direccion->direccion_calle }}</div>
                    <div><span class="font-bold">Ciudad:</span> {{ $direccion->ciudad }}</div>
                    <div><span class="font-bold">Estado:</span> {{ $direccion->estado }}</div>
                    <div><span class="font-bold">Código Postal:</span> {{ $direccion->codigo_postal }}</div>
                </div>
            @else
                <p class="text-gray-400">No tienes una dirección principal registrada.</p>
            @endif
        </div>
        <!-- Acciones -->
        <div class="mt-10 flex flex-col md:flex-row gap-4 justify-center">
            <a href="/mis-pedidos" class="cyber-button px-6 py-3 rounded-md text-white">Ver todos mis pedidos</a>
            <a href="/mis-direcciones" class="cyber-button px-6 py-3 rounded-md text-white">Gestionar direcciones</a>
            <a href="/logout" class="cyber-button px-6 py-3 rounded-md text-white">Cerrar sesión</a>
        </div>
    </div>
</div>

<style>
.cyber-glitch-text {
    position: relative;
    text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                -0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
                0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    animation: glitch 500ms infinite;
}
</style>
