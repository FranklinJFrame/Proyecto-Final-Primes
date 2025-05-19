<div class="w-full max-w-4xl py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="flex items-center font-poppins dark:bg-gray-800">
        <div class="flex-1 max-w-4xl px-6 py-8 mx-auto bg-white border rounded-2xl shadow-lg dark:border-gray-900 dark:bg-gray-900">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold tracking-wide text-blue-700 dark:text-blue-300 flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    ¡Gracias! Su orden ha sido recibida
                </h1>
                <img src="/logo-tecnobox.png" alt="TECNOBOX" class="h-10">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Datos del Cliente</h2>
                    <div class="text-gray-800 dark:text-gray-400">
                        <div class="font-bold">{{ $pedido->nombre }} {{ $pedido->apellido }}</div>
                        <div>{{ $pedido->direccion_calle }}</div>
                        <div>{{ $pedido->ciudad }}, {{ $pedido->estado_direccion }}, {{ $pedido->codigo_postal }}</div>
                        <div>Tel: {{ $pedido->telefono }}</div>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Datos de la Orden</h2>
                    <div class="text-gray-800 dark:text-gray-400">
                        <div><span class="font-semibold">N° Pedido:</span> #{{ $pedido->id }}</div>
                        <div><span class="font-semibold">Fecha:</span> {{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                        <div><span class="font-semibold">Método de pago:</span> 
                            {{ strtoupper($pedido->metodo_pago) }}
                            @if($pedido->metodo_pago === 'stripe' || $pedido->metodo_pago === 'tarjeta')
                                <span class="ml-2">(**** **** **** {{ $pedido->ultimos4 ?? '1234' }})</span>
                            @endif
                        </div>
                        <div><span class="font-semibold">Estado:</span> {{ ucfirst($pedido->estado_pago) }}</div>
                    </div>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Detalle de Productos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-900 rounded-xl shadow">
                        <thead>
                            <tr class="bg-blue-50 dark:bg-gray-800 text-blue-700 dark:text-blue-300">
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Cantidad</th>
                                <th class="p-3 text-left">Precio unitario</th>
                                <th class="p-3 text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->productos as $item)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="flex items-center gap-3 p-3">
                                        <img src="{{
                                            $item->producto && $item->producto->imagenes && is_array($item->producto->imagenes) && count($item->producto->imagenes) > 0
                                                ? (filter_var($item->producto->imagenes[0], FILTER_VALIDATE_URL)
                                                    ? $item->producto->imagenes[0]
                                                    : asset('storage/products/' . $item->producto->imagenes[0]))
                                                : 'https://placehold.co/60x60/png?text=Sin+Imagen'
                                        }}" class="w-12 h-12 object-contain rounded bg-gray-100 border border-gray-200" alt="Imagen del producto">
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->producto->nombre ?? 'Producto eliminado' }}</span>
                                    </td>
                                    <td class="p-3">{{ $item->cantidad }}</td>
                                    <td class="p-3">RD$ {{ number_format($item->precio_unitario, 2) }}</td>
                                    <td class="p-3">RD$ {{ number_format($item->precio_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div></div>
                <div class="bg-blue-50 dark:bg-gray-800 rounded-xl p-6 flex flex-col gap-2">
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-blue-800 font-semibold">RD$ {{ number_format($pedido->productos->sum('precio_total') - $pedido->costo_envio - round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">ITBIS (18%)</span>
                        <span class="text-blue-800 font-semibold">RD$ {{ number_format(round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Envío</span>
                        <span class="text-blue-800 font-semibold">RD$ {{ number_format($pedido->costo_envio, 2) }}</span>
                    </div>
                    <div class="border-t border-blue-200 my-2"></div>
                    <div class="flex justify-between text-lg font-bold">
                        <span class="text-blue-800">Total</span>
                        <span class="text-blue-600">RD$ {{ number_format($pedido->productos->sum('precio_total'), 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4 mt-8">
                <div class="flex gap-2">
                    <a href="/products" class="px-6 py-3 text-blue-600 border border-blue-600 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition">Volver a la tienda</a>
                    <a href="/my-orders" class="px-6 py-3 bg-blue-600 rounded-lg text-white font-semibold hover:bg-blue-700 transition">Ver mis pedidos</a>
                </div>
                <form method="POST" action="{{ route('factura.pdf', $pedido->id) }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 rounded-lg text-white font-semibold hover:bg-green-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Descargar PDF
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>