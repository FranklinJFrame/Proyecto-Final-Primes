<div class="min-h-screen bg-black text-white">
    <!-- Fondo animado cyber -->
    <div class="relative">
        <div class="absolute inset-0 bg-gradient-radial"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20 animate-grid"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Cabecera con efecto cyber -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-white mb-2">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-blue-600 animate-gradient">
                    Centro de Control
                </span>
            </h1>
            <p class="text-lg text-gray-300 animate-pulse-slow">
                Bienvenido de vuelta, <span class="font-bold text-blue-400">{{ auth()->user()->name }}</span>
            </p>
            <div class="mt-2 cyber-tag inline-block">
                {{ now()->format('H:i:s') }}
            </div>
        </div>

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel izquierdo: Datos personales -->
            <div class="cyber-panel rounded-xl p-6 border border-blue-500/30 backdrop-blur-sm hover:border-blue-500 transition-all duration-300">
                <h2 class="text-xl font-bold mb-6 text-blue-400 cyber-glitch-text">Datos Personales</h2>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-400 block text-sm">Nombre:</span>
                        <span class="text-white font-bold">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-sm">Email:</span>
                        <span class="text-white">{{ auth()->user()->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-sm">Teléfono:</span>
                        <span class="text-white">{{ auth()->user()->telefono ?? 'No definido' }}</span>
                    </div>

                    <!-- Información de cuenta -->
                    <div class="pt-4 border-t border-blue-500/20">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-400">ID de Usuario:</span>
                            <span class="text-white font-mono">#00{{ auth()->id() }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-400">Estado:</span>
                            <span class="text-green-400">Activo</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Miembro desde:</span>
                            <span class="text-white">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel central: Últimos pedidos -->
            <div class="cyber-panel rounded-xl p-6 border border-purple-500/30 backdrop-blur-sm hover:border-purple-500 transition-all duration-300">
                <h2 class="text-xl font-bold mb-6 text-purple-400 cyber-glitch-text">Últimos Pedidos</h2>
                
                @php
                    $ultimosPedidos = auth()->user()->pedidos()->latest()->take(3)->get();
                @endphp
                
                @if($ultimosPedidos->count() > 0)
                    <div class="space-y-4">
                        @foreach($ultimosPedidos as $pedido)
                            <div class="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20 hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1 group shadow-glow-purple">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-bold text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Pedido #{{ $pedido->id }}
                                    </span>
                                    <span class="cyber-tag bg-purple-900/50 border-purple-500/30 group-hover:bg-purple-800/60 group-hover:border-purple-400/40 transition-all duration-300">
                                        {{ $pedido->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <!-- Productos del pedido (miniaturas) -->
                                <div class="flex gap-1 mb-3 overflow-x-auto py-1 scrollbar-thin scrollbar-thumb-purple-500 scrollbar-track-gray-800">
                                    @foreach($pedido->productos()->with('producto')->take(3)->get() as $item)
                                        <div class="relative flex-shrink-0">
                                            <img 
                                                src="{{ 
                                                    $item->producto->imagenes && is_array($item->producto->imagenes) && count($item->producto->imagenes) > 0
                                                        ? (filter_var($item->producto->imagenes[0], FILTER_VALIDATE_URL)
                                                            ? $item->producto->imagenes[0]
                                                            : asset('storage/products/' . $item->producto->imagenes[0]))
                                                        : 'https://placehold.co/80x80/png?text=Sin+Imagen'
                                                }}" 
                                                alt="{{ $item->producto->nombre }}" 
                                                class="w-10 h-10 rounded-md product-image"
                                            >
                                            @if($item->cantidad > 1)
                                                <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $item->cantidad }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if($pedido->productos()->count() > 3)
                                        <div class="relative flex-shrink-0 w-10 h-10 rounded-md bg-purple-900/30 border border-purple-500/30 flex items-center justify-center text-purple-300 text-xs font-bold">
                                            +{{ $pedido->productos()->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Total:
                                    </span>
                                    <span class="text-lg font-bold text-purple-400 group-hover:text-purple-300 transition-colors duration-300">
                                        RD$ {{ number_format($pedido->total_general, 2) }}
                                    </span>
                                </div>
                                
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="text-xs text-purple-300/70">
                                        <span class="inline-flex items-center gap-1 {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }}">
                                            @if($pedido->estado === 'entregado')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($pedido->estado === 'cancelado')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @else
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                            {{ ucfirst($pedido->estado) }}
                                        </span>
                                    </span>
                                    <a href="/my-orders/{{ $pedido->id }}" class="text-sm text-purple-400 hover:text-purple-300 flex items-center gap-1 group-hover:translate-x-1 transition-all duration-300">
                                        Ver detalles
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4 text-center">
                            <a href="/my-orders" class="cyber-button px-4 py-2 rounded-md text-white inline-block">
                                Ver historial completo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-800/50 rounded-lg p-6 text-center border border-purple-500/20">
                        <p class="text-gray-400 mb-4">No tienes pedidos recientes.</p>
                        <a href="/products" class="cyber-button px-4 py-2 rounded-md text-white inline-block">
                            Explorar productos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Panel derecho: Dirección y acciones -->
            <div class="space-y-6">
                <!-- Dirección principal -->
                <div class="cyber-panel rounded-xl p-6 border border-red-500/30 backdrop-blur-sm hover:border-red-500 transition-all duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-red-400 cyber-glitch-text">Mis Direcciones</h2>
                        <div>
                            @if($direcciones->count() < 3)
                                <button wire:click="nuevaDireccion" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm flex items-center gap-1 transition-all duration-300 shadow-glow-red">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Nueva
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if(session('error'))
                        <div class="bg-red-900/50 text-red-200 p-3 rounded-lg mb-4 border border-red-500/30 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-900/50 text-green-200 p-3 rounded-lg mb-4 border border-green-500/30 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($mostrarFormulario)
                        <div class="bg-gray-800/80 rounded-lg p-5 border border-red-500/30 mb-4 animate-fade-in shadow-glow-red">
                            <h3 class="text-lg font-semibold text-red-400 mb-4">{{ $modo === 'crear' ? 'Nueva Dirección' : 'Editar Dirección' }}</h3>
                            
                            <form wire:submit.prevent="{{ $modo === 'crear' ? 'save' : 'update' }}" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Nombre</label>
                                        <input wire:model.defer="nombre" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Apellido</label>
                                        <input wire:model.defer="apellido" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Teléfono</label>
                                        <input wire:model.defer="telefono" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-gray-400 text-xs mb-1 block">Dirección</label>
                                        <input wire:model.defer="direccion_calle" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Ciudad</label>
                                        <input wire:model.defer="ciudad" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Estado</label>
                                        <input wire:model.defer="estado" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-400 text-xs mb-1 block">Código Postal</label>
                                        <input wire:model.defer="codigo_postal" type="text" class="w-full bg-gray-900/50 border border-red-500/20 rounded-md p-2 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-300" required>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition-all duration-300">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-md transition-all duration-300 shadow-glow-red">{{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    @if($direcciones->count() > 0)
                        <div class="space-y-4">
                            @foreach($direcciones as $dir)
                                <div class="bg-gray-800/50 rounded-lg p-4 border border-red-500/20 hover:border-red-500/50 transition-all duration-300 group relative overflow-hidden">
                                    <!-- Efecto de hover cyber -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 via-red-500/5 to-red-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="absolute inset-0 overflow-hidden">
                                        <div class="h-px w-full bg-gradient-to-r from-transparent via-red-500/50 to-transparent transform -translate-x-full group-hover:translate-x-0 transition-transform duration-1000"></div>
                                    </div>
                                    
                                    <div class="relative z-10">
                                        <div class="flex justify-between">
                                            <div class="font-bold text-white">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                                            <div class="text-xs text-red-400 cyber-tag">{{ $loop->index === 0 ? 'Principal' : 'Alternativa ' . $loop->index }}</div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="mb-1">
                                                <span class="text-gray-400 text-sm">Dirección:</span>
                                                <span class="text-white">{{ $dir->direccion_calle }}</span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-gray-400 text-sm">Ciudad:</span>
                                                <span class="text-white">{{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Teléfono:</span>
                                                <span class="text-white">{{ $dir->telefono }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-end gap-2">
                                            <button wire:click="edit({{ $dir->id }})" class="px-3 py-1 bg-yellow-600 hover:bg-yellow-500 text-white rounded-md text-sm transition-all duration-300 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Editar
                                            </button>
                                            <button wire:click="delete({{ $dir->id }})" class="px-3 py-1 bg-red-700 hover:bg-red-600 text-white rounded-md text-sm transition-all duration-300 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-800/50 rounded-lg p-6 text-center border border-red-500/20">
                            <p class="text-gray-400 mb-4">No tienes direcciones registradas.</p>
                            <button wire:click="nuevaDireccion" class="cyber-button px-4 py-2 rounded-md text-white inline-block">
                                Añadir dirección
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Botón de Cerrar Sesión -->
                <div class="mt-4 text-center">
                    <form method="POST" action="/logout" class="inline-block">
                        @csrf
                        <button type="submit" class="cyber-button px-4 py-3 rounded-md text-white">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<style>
/* Animaciones base */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

@keyframes grid {
    0% { transform: translateX(0) translateY(0); }
    100% { transform: translateX(-100%) translateY(-100%); }
}

@keyframes pulse-glow {
    0% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
    50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); }
    100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes fade-in {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Clases de animación */
.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-grid {
    animation: grid 20s linear infinite;
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}

/* Efectos de sombra cyber */
.shadow-glow-red {
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-red:hover {
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.5), 0 0 30px rgba(239, 68, 68, 0.2);
}

.shadow-glow-blue {
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-blue:hover {
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.5), 0 0 30px rgba(59, 130, 246, 0.2);
}

.shadow-glow-purple {
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.3);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-purple:hover {
    box-shadow: 0 0 15px rgba(139, 92, 246, 0.5), 0 0 30px rgba(139, 92, 246, 0.2);
}

/* Mejora para las imágenes de productos */
.product-image {
    background: linear-gradient(45deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
    border: 1px solid rgba(139, 92, 246, 0.3);
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.2);
    object-fit: contain;
    transition: all 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
    border-color: rgba(139, 92, 246, 0.5);
}

/* Estilos para la barra de desplazamiento */
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thumb-purple-500::-webkit-scrollbar-thumb {
    background-color: rgba(139, 92, 246, 0.5);
    border-radius: 9999px;
}

.scrollbar-track-gray-800::-webkit-scrollbar-track {
    background-color: rgba(31, 41, 55, 0.4);
    border-radius: 9999px;
}

::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(139, 92, 246, 0.5);
    border-radius: 9999px;
}

::-webkit-scrollbar-track {
    background-color: rgba(31, 41, 55, 0.4);
    border-radius: 9999px;
}


.animate-pulse-slow {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 8s ease infinite;
}

/* Estilos Cyber */
.bg-gradient-radial {
    background: radial-gradient(circle at center, rgba(59, 130, 246, 0.1) 0%, rgba(0, 0, 0, 1) 70%);
    animation: pulse-glow 4s ease-in-out infinite;
}

.bg-grid-pattern {
    background-image: linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
                     linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

.cyber-button {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    box-shadow: inset 0 0 10px rgba(59, 130, 246, 0.2);
    transition: all 0.3s ease;
}

.cyber-button:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: inset 0 0 20px rgba(59, 130, 246, 0.3),
               0 0 15px rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
}

.cyber-tag {
    background: rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(59, 130, 246, 0.3);
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(4px);
}

.cyber-panel {
    background: rgba(17, 24, 39, 0.7);
    border-radius: 0.75rem;
    overflow: hidden;
    backdrop-filter: blur(4px);
}

.cyber-glitch-text {
    position: relative;
    text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                -0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
                0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    animation: glitch 500ms infinite;
}

@keyframes glitch {
    0% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                    -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    }
    14% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                    -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
    }
    15% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                    0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    49% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                    0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    50% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                    0.05em 0 0 rgba(0, 255, 0, 0.75),
                    0 -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    99% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                    0.05em 0 0 rgba(0, 255, 0, 0.75),
                    0 -0.05em 0 rgba(0, 0, 255, 0.75);
    }
    100% {
        text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75),
                    -0.025em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em -0.05em 0 rgba(0, 0, 255, 0.75);
    }
}
</style>
</div>
