<!-- Contenedor principal con gradiente sutil -->
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white pb-20">
    <!-- Header con diseño mejorado -->
    <div class="bg-gray-800/50 border-b border-gray-700/50 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Mis Pedidos
            </h1>
            <p class="text-lg text-gray-400">
                Gestiona y revisa el historial de tus compras
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Panel de filtros con diseño mejorado -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 mb-8 shadow-xl">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                    <div class="relative group w-full md:w-auto">
                        <select class="w-full md:w-auto bg-gray-900/50 border-gray-600 rounded-xl pl-4 pr-10 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 appearance-none transition-all hover:bg-gray-700/50">
                            <option value="all">Todos los pedidos</option>
                            <option value="last30">Últimos 30 días</option>
                            <option value="last3months">Últimos 3 meses</option>
                            <option value="last6months">Últimos 6 meses</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative group w-full md:w-auto">
                        <select class="w-full md:w-auto bg-gray-900/50 border-gray-600 rounded-xl pl-4 pr-10 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 appearance-none transition-all hover:bg-gray-700/50">
                            <option value="all">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="processing">En proceso</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregado</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="relative flex-1 md:max-w-xs w-full">
                    <input type="text" placeholder="Buscar pedido..." 
                        class="w-full bg-gray-900/50 border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all hover:bg-gray-700/50">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de pedidos con diseño mejorado -->
        <div class="space-y-6">
            @forelse($pedidos as $pedido)
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl hover:shadow-2xl transition-all hover:border-gray-600/50 group">
                    <!-- Cabecera del pedido -->
                    <div class="bg-gray-900/50 p-6">
                        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 w-full md:w-auto">
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-400 mb-1">Pedido realizado</div>
                                    <div class="font-medium text-lg">{{ $pedido->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-400 mb-1">Total</div>
                                    <div class="font-medium text-lg text-blue-400">RD$ {{ number_format($pedido->total_general, 2) }}</div>
                                    <button type="button" class="text-xs text-gray-400 hover:text-blue-400 mt-1" onclick="toggleDesglose{{ $pedido->id }}()">
                                        Ver desglose
                                    </button>
                                    <div id="desglose{{ $pedido->id }}" class="hidden mt-2 text-xs space-y-1 bg-gray-800/80 p-2 rounded">
                                        @php
                                            $subtotal = $pedido->productos->sum(function($item) {
                                                return $item->precio_unitario * $item->cantidad;
                                            });
                                            $itbis = round($subtotal * 0.18, 2);
                                            $envio = $pedido->costo_envio;
                                        @endphp
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span>RD$ {{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>ITBIS (18%):</span>
                                            <span>RD$ {{ number_format($itbis, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Envío:</span>
                                            <span>RD$ {{ number_format($envio, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-400 mb-1">Pedido #</div>
                                    <div class="font-medium text-lg">{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                                @switch($pedido->estado)
                                    @case('nuevo')
                                        <div class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 rounded-xl">
                                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                            <span class="text-blue-400 font-medium">Nuevo</span>
                                        </div>
                                        @break
                                    @case('procesando')
                                        <div class="flex items-center gap-2 px-4 py-2 bg-yellow-500/10 rounded-xl">
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                            <span class="text-yellow-400 font-medium">Procesando</span>
                                        </div>
                                        @break
                                    @case('enviado')
                                        <div class="flex items-center gap-2 px-4 py-2 bg-purple-500/10 rounded-xl">
                                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                            <span class="text-purple-400 font-medium">Enviado</span>
                                        </div>
                                        @break
                                    @case('entregado')
                                        <div class="flex items-center gap-2 px-4 py-2 bg-green-500/10 rounded-xl">
                                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                            <span class="text-green-400 font-medium">Entregado</span>
                                        </div>
                                        @break
                                    @default
                                        <div class="flex items-center gap-2 px-4 py-2 bg-gray-500/10 rounded-xl">
                                            <span class="text-gray-400 font-medium">{{ ucfirst($pedido->estado) }}</span>
                                        </div>
                                @endswitch
                                <a href="{{ route('pedidos.detalle', $pedido->id) }}" 
                                    class="inline-flex items-center justify-center px-6 py-2 rounded-xl
                                    text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Productos del pedido -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pedido->productos()->with('producto')->get() as $item)
                                <div class="flex items-start gap-4 bg-gray-900/50 rounded-xl p-4 hover:bg-gray-700/30 transition-colors group-hover:border-gray-600">
                                    <img src="{{$item->producto->imagenes[0]}}" 
                                    class="w-24 h-24 object-cover rounded-lg bg-gray-800 border border-gray-700" 
                                    alt="{{ $item->producto->nombre }}">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-white font-medium text-lg line-clamp-2 mb-1">{{ $item->producto->nombre }}</div>
                                        <div class="text-gray-400 mb-2">Cantidad: {{ $item->cantidad }}</div>
                                        <div class="text-blue-400 font-bold text-lg">
                                            RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Acciones del pedido -->
                        <div class="mt-6 flex flex-wrap gap-4 items-center justify-between border-t border-gray-700/50 pt-6">
                            <div class="flex flex-wrap items-center gap-4">
                                @if($pedido->estado === 'entregado')
                                    <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-700/30 hover:bg-gray-700/50 transition-colors">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Escribir reseña</span>
                                    </button>
                                @endif
                                <a href="{{ route('factura.pdf.get', $pedido->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-700/30 hover:bg-gray-700/50 transition-colors">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Descargar factura</span>
                                </a>
                                <button wire:click="comprarDeNuevo({{ $pedido->id }})" 
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-700/30 hover:bg-gray-700/50 transition-colors">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Comprar de nuevo</span>
                                </button>
                            </div>
                            @if($pedido->estado !== 'entregado')
                                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-500/10">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-blue-400">
                                        @switch($pedido->estado)
                                            @case('nuevo')
                                                Preparando tu pedido
                                                @break
                                            @case('procesando')
                                                En proceso de empaque
                                                @break
                                            @case('enviado')
                                                En camino a tu dirección
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-12 border border-gray-700/50 text-center shadow-xl">
                    <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gray-700/50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">No tienes pedidos aún</h3>
                        <p class="text-gray-400 mb-8">¡Explora nuestra tienda y encuentra productos increíbles para comenzar tu historia de compras con nosotros!</p>
                        <a href="/products" 
                            class="inline-flex items-center justify-center px-8 py-3 rounded-xl
                            text-base font-medium text-white bg-blue-600 hover:bg-blue-500 transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">
                            Explorar la tienda
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($pedidos->hasPages())
            <div class="mt-8">
                <div class="flex justify-center">
                    {{ $pedidos->links() }}
                </div>
            </div>
        @endif

        <!-- Sección de ayuda mejorada -->
        <div class="mt-12 bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 text-center shadow-xl">
            <div class="flex flex-col items-center justify-center gap-2">
                <svg class="w-8 h-8 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-lg font-semibold text-white">¿Necesitas ayuda?</h4>
                <p class="text-gray-400">
                    Nuestro equipo está disponible 24/7 para ayudarte con cualquier duda sobre tus pedidos
                </p>
                <a href="/contacto" class="mt-4 inline-flex items-center justify-center px-6 py-2 rounded-xl
                    text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">
                    Contactar soporte
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Funciones para mostrar/ocultar el desglose del total
    @foreach($pedidos as $pedido)
    function toggleDesglose{{ $pedido->id }}() {
        const desglose = document.getElementById('desglose{{ $pedido->id }}');
        if (desglose.classList.contains('hidden')) {
            desglose.classList.remove('hidden');
        } else {
            desglose.classList.add('hidden');
        }
    }
    @endforeach
</script>
@endpush 