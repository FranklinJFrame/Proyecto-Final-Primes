<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Finalizar Pedido</h2>

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="realizarPedido">
            <!-- Dirección de Envío -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Dirección de Envío</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($direcciones as $direccion)
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model="direccionId" value="{{ $direccion->id }}" class="h-4 w-4 text-indigo-600">
                            <div class="ml-4">
                                <p class="font-medium">{{ $direccion->nombre_completo }}</p>
                                <p class="text-gray-600">{{ $direccion->direccion_calle }}</p>
                                <p class="text-gray-600">{{ $direccion->ciudad }}, {{ $direccion->estado }} {{ $direccion->codigo_postal }}</p>
                                <p class="text-gray-600">Tel: {{ $direccion->telefono }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('direccionId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Método de Pago -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Método de Pago</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($metodosPago as $metodo)
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model="metodoPago" value="{{ $metodo->codigo }}" class="h-4 w-4 text-indigo-600">
                            <span class="ml-2">{{ $metodo->nombre }}</span>
                        </label>
                    @endforeach
                </div>
                @error('metodoPago') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Resumen del Pedido -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Resumen del Pedido</h3>
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
                            @foreach($productos as $item)
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
                                        {{ number_format($item->precio_unitario, 2) }} {{ $moneda }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($item->cantidad * $item->precio_unitario, 2) }} {{ $moneda }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                <td class="px-6 py-4 text-right font-bold">{{ number_format($total, 2) }} {{ $moneda }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Notas -->
            <div class="mb-6">
                <label for="notas" class="block text-sm font-medium text-gray-700">Notas adicionales</label>
                <textarea id="notas" wire:model="notas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Botón de Pago -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Realizar Pedido
                </button>
            </div>
        </form>
    </div>
</div> 