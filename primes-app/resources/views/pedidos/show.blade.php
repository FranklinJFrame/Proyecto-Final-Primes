<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Pedido #{{ $pedido->id }}</h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($pedido->estado === 'nuevo') bg-blue-100 text-blue-800
                    @elseif($pedido->estado === 'procesando') bg-yellow-100 text-yellow-800
                    @elseif($pedido->estado === 'enviado') bg-green-100 text-green-800
                    @elseif($pedido->estado === 'entregado') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($pedido->estado) }}
                </span>
            </div>

            <!-- Estado del Pago -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Estado del Pago</h3>
                <div class="flex items-center">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($pedido->estado_pago === 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($pedido->estado_pago === 'pagado') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($pedido->estado_pago) }}
                    </span>
                    <span class="ml-4 text-gray-600">Método: {{ $pedido->pago->metodoPago->nombre }}</span>
                </div>
            </div>

            <!-- Información de Envío -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Dirección de Envío</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-medium">{{ $pedido->direccion->nombre_completo }}</p>
                    <p class="text-gray-600">{{ $pedido->direccion->direccion_calle }}</p>
                    <p class="text-gray-600">{{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }} {{ $pedido->direccion->codigo_postal }}</p>
                    <p class="text-gray-600">Tel: {{ $pedido->direccion->telefono }}</p>
                </div>
            </div>

            <!-- Productos -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Productos</h3>
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pedido->productos as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($item->producto->imagenes)
                                                <img src="{{ Storage::url(json_decode($item->producto->imagenes)[0]) }}" class="h-10 w-10 rounded-full object-cover">
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->producto->nombre }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">{{ $item->cantidad }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        {{ number_format($item->precio_unitario, 2) }} {{ $pedido->moneda }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($item->precio_total, 2) }} {{ $pedido->moneda }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                <td class="px-6 py-4 text-right font-bold">{{ number_format($pedido->total_general, 2) }} {{ $pedido->moneda }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($pedido->notas)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Notas</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-600">{{ $pedido->notas }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 