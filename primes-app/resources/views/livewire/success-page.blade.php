<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado con animación -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500/20 rounded-full mb-6 animate-bounce">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">¡Gracias por tu compra!</h1>
            <p class="text-xl text-gray-300">Tu pedido ha sido procesado exitosamente</p>
        </div>

        <!-- Tarjeta principal con efecto de vidrio -->
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-8 shadow-2xl">
            <!-- Información del pedido -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h2 class="text-lg font-semibold text-blue-400 mb-4">Datos del Cliente</h2>
                    <div class="space-y-2 text-gray-300">
                        <div class="font-medium text-white">{{ $pedido->nombre }} {{ $pedido->apellido }}</div>
                        <div>{{ $pedido->direccion_calle }}</div>
                        <div>{{ $pedido->ciudad }}, {{ $pedido->estado_direccion }}, {{ $pedido->codigo_postal }}</div>
                        <div>Tel: {{ $pedido->telefono }}</div>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-blue-400 mb-4">Detalles del Pedido</h2>
                    <div class="space-y-2 text-gray-300">
                        <div class="flex justify-between">
                            <span>N° Pedido:</span>
                            <span class="text-white font-medium">#{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Fecha:</span>
                            <span class="text-white font-medium">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Método de pago:</span>
                            <span class="text-white font-medium">
                                {{ strtoupper($pedido->metodo_pago) }}
                                @if($pedido->metodo_pago === 'stripe' || $pedido->metodo_pago === 'tarjeta')
                                    <span class="text-gray-400 ml-2">(**** **** **** {{ $pedido->ultimos4 ?? '1234' }})</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estado:</span>
                            <span class="text-green-400 font-medium">{{ ucfirst($pedido->estado_pago) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de productos -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-blue-400 mb-4">Productos Comprados</h2>
                <div class="space-y-4">
                    @foreach($pedido->productos as $item)
                        <div class="flex items-center gap-4 bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                            <img src="{{ url('storage/products/' . ($item->producto->imagenes[0] ?? '')) }}" 
                            class="w-16 h-16 object-cover rounded-lg bg-gray-800 border border-gray-700" 
                            alt="{{ $item->producto->nombre }}">
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-white">{{ $item->producto->nombre }}</h3>
                                <p class="text-sm text-gray-400">Cantidad: {{ $item->cantidad }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-blue-400 font-bold">RD$ {{ number_format($item->precio_total, 2) }}</div>
                                <div class="text-sm text-gray-400">RD$ {{ number_format($item->precio_unitario, 2) }} c/u</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Resumen de costos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-start-2">
                    <div class="bg-white/5 rounded-xl p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal</span>
                                <span class="font-medium">RD$ {{ number_format($pedido->productos->sum('precio_total') - $pedido->costo_envio - round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>ITBIS (18%)</span>
                                <span class="font-medium">RD$ {{ number_format(round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>Envío</span>
                                <span class="font-medium">RD$ {{ number_format($pedido->costo_envio, 2) }}</span>
                            </div>
                            <div class="border-t border-white/10 my-3"></div>
                            <div class="flex justify-between text-lg font-bold text-white">
                                <span>Total</span>
                                <span>RD$ {{ number_format($pedido->productos->sum('precio_total'), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-wrap items-center justify-between gap-4 mt-8 pt-8 border-t border-white/10">
                <div class="flex gap-4">
                    <a href="/products" 
                        class="inline-flex items-center px-6 py-3 rounded-xl text-white border border-white/20 hover:bg-white/5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Seguir comprando
                    </a>
                    <a href="/my-orders" 
                        class="inline-flex items-center px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all">
                        Ver mis pedidos
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                <form method="POST" action="{{ route('factura.pdf', $pedido->id) }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar Factura
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>