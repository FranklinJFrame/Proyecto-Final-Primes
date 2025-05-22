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