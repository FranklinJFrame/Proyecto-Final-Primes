<div>
    <div class="min-h-screen bg-gradient-to-br from-[#0a2342] via-[#122e5c] to-[#0a2342] text-white">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Cabecera -->
            <div class="text-center mb-16 relative">
                <h1 class="text-6xl font-extrabold text-white drop-shadow-lg tracking-tight mb-2 animate-pulse">Mi Cuenta</h1>
                <p class="text-xl text-blue-200">Bienvenido, <span class="font-bold text-blue-400">{{ auth()->user()->name }}</span></p>
            </div>
            <!-- Grid principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Datos Personales -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-blue-700/30 shadow-2xl hover:shadow-blue-700/20 transition-all duration-300 h-[480px] flex flex-col overflow-hidden">
                    <h2 class="text-2xl font-bold mb-6 text-blue-300 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Datos Personales
                    </h2>
                    <div class="space-y-6 overflow-y-auto pr-2 custom-scrollbar flex-1">
                        <div class="bg-blue-900/30 rounded-xl p-4">
                            <span class="text-blue-200 block text-sm mb-1">Nombre:</span>
                            <span class="text-white font-bold text-lg">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="bg-blue-900/30 rounded-xl p-4">
                            <span class="text-blue-200 block text-sm mb-1">Email:</span>
                            <span class="text-white text-lg">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="bg-blue-900/30 rounded-xl p-4">
                            <span class="text-blue-200 block text-sm mb-1">Teléfono:</span>
                            <span class="text-white text-lg">{{ auth()->user()->telefono ?? 'No definido' }}</span>
                        </div>
                        <div class="pt-6 border-t border-blue-700/20">
                            <div class="flex justify-between text-sm mb-3 bg-blue-900/30 rounded-xl p-4">
                                <span class="text-blue-200">ID de Usuario:</span>
                                <span class="text-white font-mono">#00{{ auth()->id() }}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-3 bg-blue-900/30 rounded-xl p-4">
                                <span class="text-blue-200">Estado:</span>
                                <span class="text-green-400 font-bold">Activo</span>
                            </div>
                            <div class="flex justify-between text-sm bg-blue-900/30 rounded-xl p-4">
                                <span class="text-blue-200">Miembro desde:</span>
                                <span class="text-white">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Últimos Pedidos -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-blue-700/30 shadow-2xl hover:shadow-blue-700/20 transition-all duration-300 h-[480px] flex flex-col overflow-hidden">
                    <h2 class="text-2xl font-bold mb-6 text-blue-300 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        Últimos Pedidos
                    </h2>
                    <div class="space-y-6 overflow-y-auto pr-2 custom-scrollbar flex-1">
                        @php
                            $ultimosPedidos = auth()->user()->pedidos()->with('productos.producto')->latest()->take(3)->get();
                        @endphp
                        @if($ultimosPedidos->count() > 0)
                            @foreach($ultimosPedidos as $pedido)
                                <div class="bg-blue-900/30 rounded-xl p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-blue-200 text-sm">Pedido #{{ $pedido->id }}</span>
                                        <span class="text-sm {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }} font-bold">
                                            {{ ucfirst($pedido->estado) }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 mb-4 overflow-x-auto py-1">
                                        @foreach($pedido->productos()->with('producto')->take(3)->get() as $item)
                                            <div class="relative flex-shrink-0 group">
                                                <img src="{{ $item->producto && isset($item->producto->imagenes[0]) ? url('storage', $item->producto->imagenes[0]) : asset('img/default-product.jpg') }}" alt="{{ $item->producto->nombre ?? 'Producto' }}" class="w-16 h-16 rounded-xl object-cover bg-blue-800 group-hover:scale-110 transition-all duration-300" loading="lazy" onerror="this.src='/img/default-product.jpg'">
                                                @if($item->cantidad > 1)
                                                    <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow-lg">{{ $item->cantidad }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($pedido->productos()->count() > 3)
                                            <div class="relative flex-shrink-0 w-16 h-16 rounded-xl bg-blue-800 flex items-center justify-center text-blue-200 text-sm font-bold shadow-lg">+{{ $pedido->productos()->count() - 3 }}</div>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-blue-200">Total:</span>
                                        <span class="text-xl font-bold text-blue-400">RD$ {{ number_format($pedido->total_general, 2) }}</span>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-sm {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }}">{{ ucfirst($pedido->estado) }}</span>
                                        <a href="{{ route('pedidos.detalle', $pedido->id) }}" class="text-sm text-blue-300 hover:text-blue-200 flex items-center gap-1 transition-all duration-300 hover:gap-2">Ver detalles<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></a>
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-6 text-center">
                                <a href="{{ route('pedidos') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">Ver historial completo</a>
                            </div>
                        @else
                            <div class="bg-blue-900/30 rounded-xl p-8 text-center">
                                <p class="text-blue-200 mb-6 text-lg">No tienes pedidos recientes.</p>
                                <a href="/products" class="inline-flex items-center justify-center px-6 py-3 rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">Explorar productos</a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Mis Direcciones -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-blue-700/30 shadow-2xl hover:shadow-blue-700/20 transition-all duration-300 h-[480px] flex flex-col overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-blue-300 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Mis Direcciones
                        </h2>
                        <div>
                            @if($direcciones->count() < 3)
                                <button wire:click="nuevaDireccion" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Nueva</button>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4 overflow-y-auto pr-2 custom-scrollbar flex-1">
                        @if($direcciones->count() > 0)
                            @foreach($direcciones as $dir)
                                <div class="bg-blue-900/30 rounded-xl p-6">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white text-lg">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                                        <div class="text-sm text-blue-400">{{ $loop->index === 0 ? 'Principal' : 'Alternativa ' . $loop->index }}</div>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="bg-blue-800/30 rounded-lg p-3">
                                            <span class="text-blue-200 text-sm">Dirección:</span>
                                            <span class="text-white block mt-1">{{ $dir->direccion_calle }}</span>
                                        </div>
                                        <div class="bg-blue-800/30 rounded-lg p-3">
                                            <span class="text-blue-200 text-sm">Ciudad:</span>
                                            <span class="text-white block mt-1">{{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</span>
                                        </div>
                                        <div class="bg-blue-800/30 rounded-lg p-3">
                                            <span class="text-blue-200 text-sm">Teléfono:</span>
                                            <span class="text-white block mt-1">{{ $dir->telefono }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button wire:click="edit({{ $dir->id }})" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-400 text-white rounded-xl text-sm transition-all duration-300 flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>Editar</button>
                                        <button wire:click="delete({{ $dir->id }})" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl text-sm transition-all duration-300 flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>Eliminar</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-blue-900/30 rounded-xl p-8 text-center">
                                <p class="text-blue-200 mb-6 text-lg">No tienes direcciones registradas.</p>
                                <button wire:click="nuevaDireccion" class="inline-flex items-center justify-center px-6 py-3 rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">Añadir dirección</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1976d2;
        border-radius: 8px;
    }
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #1976d2 #0a2342;
    }
    </style>
</div>
