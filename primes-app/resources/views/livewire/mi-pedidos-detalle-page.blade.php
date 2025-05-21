<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-4xl font-bold text-slate-500">Detalles del Pedido #{{ $pedido->id }}</h1>

  <!-- Grid -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-5">
    <!-- Card Cliente -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
        </div>

        <div class="grow">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cliente</h3>
          <p class="mt-1 text-xs font-medium uppercase text-gray-500">{{ $pedido->user->name }}</p>
        </div>
      </div>
    </div>
    <!-- End Card Cliente -->

    <!-- Card Fecha -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
            <path d="M16 2v4" />
            <path d="M8 2v4" />
            <path d="M3 10h18" />
          </svg>
        </div>

        <div class="grow">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Fecha</h3>
          <p class="mt-1 text-xs font-medium uppercase text-gray-500">{{ $pedido->created_at->format('d-m-Y') }}</p>
        </div>
      </div>
    </div>
    <!-- End Card Fecha -->

    <!-- Card Estado y Pago -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5">
        <div class="flex gap-x-4 mb-3">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z" />
              <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
            </svg>
          </div>

          <div class="grow">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Estado</h3>
            <span class="inline-flex items-center gap-x-1 py-1 px-3 rounded-full text-xs font-medium {{ $pedido->estado == 'Nuevo' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $pedido->estado }}</span>
          </div>
        </div>
        
        <div class="flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="14" x="2" y="5" rx="2" />
              <line x1="2" x2="22" y1="10" y2="10" />
            </svg>
          </div>

          <div class="grow">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pago</h3>
            <span class="inline-flex items-center gap-x-1 py-1 px-3 rounded-full text-xs font-medium {{ $pedido->estado_pago == 'Pagado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $pedido->estado_pago }}</span>
          </div>
        </div>
      </div>
    </div>
    <!-- End Card Pago -->
  </div>
  <!-- End Grid -->

  <div class="flex flex-col md:flex-row gap-4 mt-4">
    <div class="md:w-3/4">
      <!-- Tabla de productos -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
          <h2 class="text-lg font-semibold">Productos</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($pedido->productos as $item)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    @if($item->producto && $item->producto->imagen_principal)
                    <div class="flex-shrink-0 h-10 w-10">
                      <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/' . $item->producto->imagen_principal) }}" alt="{{ $item->producto->nombre }}">
                    </div>
                    @endif
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ $item->producto ? $item->producto->nombre : 'Producto no disponible' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">RD$ {{ number_format($item->precio_unitario, 2) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ $item->cantidad }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Dirección de envío -->
      <div class="bg-white rounded-lg shadow-md p-6 mt-4">
        <div class="flex items-center mb-4">
          <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          <h2 class="text-lg font-semibold">Dirección de Envío</h2>
        </div>
        
        @if($pedido->user && $pedido->user->direccions && $pedido->user->direccions->count() > 0)
          @php $direccion = $pedido->user->direccions->first(); @endphp
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="font-medium text-gray-800 mb-2">{{ $pedido->user->name }}</p>
            <p class="text-gray-600">{{ $direccion->direccion_calle }}</p>
            <p class="text-gray-600">{{ $direccion->ciudad }}, {{ $direccion->estado }}</p>
            <p class="text-gray-600">{{ $direccion->codigo_postal }}</p>
            <p class="text-gray-600">Tel: {{ $direccion->telefono }}</p>
          </div>
        @elseif($pedido->direccion)
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="font-medium text-gray-800 mb-2">{{ $pedido->user->name }}</p>
            <p class="text-gray-600">{{ $pedido->direccion->direccion_calle }}</p>
            <p class="text-gray-600">{{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}</p>
            <p class="text-gray-600">{{ $pedido->direccion->codigo_postal }}</p>
            <p class="text-gray-600">Tel: {{ $pedido->direccion->telefono }}</p>
          </div>
        @elseif(isset($pedido->direccion_calle))
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="font-medium text-gray-800 mb-2">{{ $pedido->nombre ?? $pedido->user->name }} {{ $pedido->apellido ?? '' }}</p>
            <p class="text-gray-600">{{ $pedido->direccion_calle }}</p>
            <p class="text-gray-600">{{ $pedido->ciudad }}, {{ $pedido->estado_direccion ?? $pedido->estado }}</p>
            <p class="text-gray-600">{{ $pedido->codigo_postal }}</p>
            <p class="text-gray-600">Tel: {{ $pedido->telefono }}</p>
          </div>
        @else
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
            <p class="text-gray-500">No hay información de dirección disponible</p>
          </div>
        @endif
      </div>
    </div>

    <div class="md:w-1/4">
      <!-- Resumen del pedido -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4">Resumen</h2>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-medium">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }), 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">ITBIS (18%):</span>
            <span class="font-medium">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }) * 0.18, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Envío:</span>
            <span class="font-medium">RD$ {{ number_format($pedido->costo_envio ?? 0, 2) }}</span>
          </div>
          <div class="pt-2 border-t border-gray-200 mt-2">
            <div class="flex justify-between font-bold">
              <span>Total:</span>
              <span>RD$ {{ number_format($pedido->total_general, 2) }}</span>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <form action="{{ route('factura.pdf', $pedido->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 flex items-center justify-center">
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