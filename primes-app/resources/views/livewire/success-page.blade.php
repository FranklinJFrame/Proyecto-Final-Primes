<div class="min-h-screen bg-gray-900 text-white py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="mb-8">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <circle class="opacity-25" cx="24" cy="24" r="20" stroke-width="4"></circle>
                    <path class="opacity-75" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M14 24l8 8 16-16"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-white mb-4">
                ¡Pedido Completado!
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Gracias por tu compra. Tu pedido ha sido procesado exitosamente.
            </p>

            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-8">
                <h2 class="text-xl font-bold text-blue-400 mb-4">Detalles del Pedido</h2>
                <div class="space-y-4">
<<<<<<< HEAD
                    <div class="flex justify-between text-gray-300">
                        <span>Número de Pedido:</span>
                        <span class="font-medium text-white">#{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-300">
                        <span>Total:</span>
                        <span class="font-medium text-white">RD$ {{ number_format($pedido->total_general, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-300">
                        <span>Estado:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500">
                            Confirmado
                        </span>
=======
                    @foreach($pedido->productos as $item)
                        <div class="flex items-center gap-4 bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                            <img src="{{ $item->producto && $item->producto->imagenes && is_array($item->producto->imagenes) && count($item->producto->imagenes) > 0
                                ? (filter_var($item->producto->imagenes[0], FILTER_VALIDATE_URL)
                                    ? $item->producto->imagenes[0]
                                    : asset('storage/products/' . $item->producto->imagenes[0]))
                                : asset('logo-tecnobox.png') }}"
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
            @php
                $subtotal = $pedido->productos->sum('precio_total') - $pedido->costo_envio - round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2);
                $itbis = round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-start-2">
                    <div class="bg-white/5 rounded-xl p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal</span>
                                <span class="font-medium">RD$ {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>ITBIS (18%)</span>
                                <span class="font-medium">RD$ {{ number_format($itbis, 2) }}</span>
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
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64
                    </div>
                </div>
            </div>

            <div class="space-x-4">
                <a href="/mis-pedidos/{{ $pedido->id }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Ver Detalles del Pedido
                </a>
                <a href="/products" class="inline-flex items-center px-6 py-3 border border-gray-600 rounded-xl text-base font-medium text-gray-300 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>
</div>