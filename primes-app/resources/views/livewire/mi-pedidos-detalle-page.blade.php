<div class="bg-gray-900 min-h-screen">
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado del pedido -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Pedido #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</h1>
        <p class="text-blue-400">{{ $pedido->created_at->format('d/m/Y') }}</p>
      </div>
    </div>
    
    <!-- Tarjetas de información -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <!-- Tarjeta Cliente -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-blue-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Cliente</h3>
            <p class="text-white text-lg font-bold">{{ $pedido->user->name }}</p>
          </div>
        </div>
      </div>
      
      <!-- Tarjeta Fecha -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-purple-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Fecha</h3>
            <p class="text-white text-lg font-bold">{{ $pedido->created_at->format('d-m-Y') }}</p>
          </div>
        </div>
      </div>
      
      <!-- Tarjeta Estado -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-{{ $pedido->estado_pago == 'Pagado' ? 'green' : 'red' }}-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-{{ $pedido->estado_pago == 'Pagado' ? 'green' : 'red' }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Estado</h3>
            <div class="flex flex-col space-y-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pedido->estado == 'Nuevo' ? 'bg-yellow-900/60 text-yellow-400 border border-yellow-500/50' : 'bg-green-900/60 text-green-400 border border-green-500/50' }}">
                {{ $pedido->estado }}
              </span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pedido->estado_pago == 'Pagado' ? 'bg-green-900/60 text-green-400 border border-green-500/50' : 'bg-red-900/60 text-red-400 border border-red-500/50' }}">
                {{ $pedido->estado_pago }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
      <div class="md:w-3/4">
        <!-- Tabla de productos -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="p-4 bg-gray-800 border-b border-gray-700 flex items-center">
            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-white">Productos</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-800">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Producto</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Precio</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cantidad</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                </tr>
              </thead>
              <tbody class="bg-gray-900 divide-y divide-gray-800">
                @foreach($pedido->productos as $item)
                <tr class="hover:bg-gray-800/50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      @if($item->producto && isset($item->producto->imagenes) && count($item->producto->imagenes) > 0)
                      <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-md object-cover border border-gray-700" src="{{ $item->producto->imagenes[0] }}" alt="{{ $item->producto->nombre }}">
                      </div>
                      @else
                      <div class="flex-shrink-0 h-10 w-10 bg-gray-800 rounded-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                      @endif
                      <div class="ml-4">
                        <div class="text-sm font-medium text-white">{{ $item->producto ? $item->producto->nombre : 'Producto no disponible' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-300">RD$ {{ number_format($item->precio_unitario, 2) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-300">{{ $item->cantidad }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-blue-400">RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Dirección de envío -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6">
          <div class="flex items-center mb-4">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-white">Dirección de Envío</h2>
          </div>
          
          @if($pedido->user && $pedido->user->direccions && $pedido->user->direccions->count() > 0)
            @php $direccion = $pedido->user->direccions->first(); @endphp
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->user->name }}</p>
              <p class="text-gray-300">{{ $direccion->direccion_calle }}</p>
              <p class="text-gray-300">{{ $direccion->ciudad }}, {{ $direccion->estado }}</p>
              <p class="text-gray-300">{{ $direccion->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $direccion->telefono }}</p>
            </div>
          @elseif($pedido->direccion)
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->user->name }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->direccion_calle }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $pedido->direccion->telefono }}</p>
            </div>
          @elseif(isset($pedido->direccion_calle))
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->nombre ?? $pedido->user->name }} {{ $pedido->apellido ?? '' }}</p>
              <p class="text-gray-300">{{ $pedido->direccion_calle }}</p>
              <p class="text-gray-300">{{ $pedido->ciudad }}, {{ $pedido->estado_direccion ?? $pedido->estado }}</p>
              <p class="text-gray-300">{{ $pedido->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $pedido->telefono }}</p>
            </div>
          @else
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700 text-center">
              <p class="text-gray-400">No hay información de dirección disponible</p>
            </div>
          @endif
        </div>
      </div>

      <div class="md:w-1/4">
        <!-- Resumen del pedido -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6">
          <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Resumen
          </h2>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-400">Subtotal:</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }), 2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">ITBIS (18%):</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }) * 0.18, 2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Envío:</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->costo_envio ?? 0, 2) }}</span>
            </div>
            <div class="pt-3 border-t border-gray-700 mt-2">
              <div class="flex justify-between font-bold">
                <span class="text-gray-300">Total:</span>
                <span class="text-blue-400 text-xl">RD$ {{ number_format($pedido->total_general, 2) }}</span>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <form action="{{ route('factura.pdf', $pedido->id) }}" method="POST">
              @csrf
              <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Descargar Factura
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
