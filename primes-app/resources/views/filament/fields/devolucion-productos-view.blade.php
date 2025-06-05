<div class="rounded-lg border border-gray-300 dark:border-gray-600 p-4">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Productos en esta Devolución:</h3>
    @if ($getRecord() && $getRecord()->devolucionProductos && $getRecord()->devolucionProductos->count() > 0)
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($getRecord()->devolucionProductos as $itemDevuelto)
                <li class="py-3">
                    <div class="flex items-center gap-4">
                        {{-- Imagen del producto --}}
                        <div class="flex-shrink-0">
                            @php
                                $imagenes = $itemDevuelto->pedidoProducto->producto->imagenes ?? [];
                                $imgSrc = (!empty($imagenes) && isset($imagenes[0]))
                                    ? $imagenes[0]
                                    : asset('img/default-product.jpg');
                                $isCloudinary = str_starts_with($imgSrc, 'http');
                            @endphp
                            <img src="{{ $isCloudinary ? $imgSrc : url('storage/' . $imgSrc) }}"
                                 alt="{{ $itemDevuelto->pedidoProducto->producto->nombre ?? 'Producto' }}"
                                 class="w-16 h-16 rounded-md object-cover bg-gray-600"
                                 loading="lazy"
                                 onerror="this.src='/img/default-product.jpg'">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $itemDevuelto->pedidoProducto->producto->nombre ?? 'Producto no disponible' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                SKU: {{ $itemDevuelto->pedidoProducto->producto->sku ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Cantidad Devuelta: {{ $itemDevuelto->cantidad_devuelta }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Precio Unit.: {{ number_format($itemDevuelto->pedidoProducto->precio_unitario, 2) }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">No hay productos especificados en esta devolución.</p>
    @endif
</div>