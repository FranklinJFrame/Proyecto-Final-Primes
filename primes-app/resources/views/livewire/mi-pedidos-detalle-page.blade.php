<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-4xl font-bold text-slate-500">Detalles del Pedido #{{ $pedido->id }}</h1>

  <!-- Grid -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-5">
    <!-- Card Cliente -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </div>
        <div class="grow">
          <div class="flex items-center gap-x-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Cliente</p>
          </div>
          <div class="mt-1 flex items-center gap-x-2">
            <div>{{ $pedido->user->name }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Card Cliente -->
    <!-- Card Fecha -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 22h14" />
            <path d="M5 2h14" />
            <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
            <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
          </svg>
        </div>
        <div class="grow">
          <div class="flex items-center gap-x-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Fecha</p>
          </div>
          <div class="mt-1 flex items-center gap-x-2">
            <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">{{ $pedido->created_at->format('d-m-Y') }}</h3>
          </div>
        </div>
      </div>
    </div>
    <!-- End Card Fecha -->
    <!-- Card Estado -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
            <path d="m12 12 4 10 1.7-4.3L22 16Z" />
          </svg>
        </div>
        <div class="grow">
          <div class="flex items-center gap-x-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Estado</p>
          </div>
          <div class="mt-1 flex items-center gap-x-2">
            <span class="py-1 px-3 rounded text-white shadow {{ $pedido->estado === 'entregado' ? 'bg-green-600' : ($pedido->estado === 'cancelado' ? 'bg-red-600' : 'bg-yellow-500') }}">
              {{ ucfirst($pedido->estado) }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- End Card Estado -->
    <!-- Card Pago -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
      <div class="p-4 md:p-5 flex gap-x-4">
        <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
          <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z" />
            <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
            <path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2" />
            <path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2" />
          </svg>
        </div>
        <div class="grow">
          <div class="flex items-center gap-x-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">Pago</p>
          </div>
          <div class="mt-1 flex items-center gap-x-2">
            <span class="py-1 px-3 rounded text-white shadow {{ $pedido->estado_pago === 'pagado' ? 'bg-green-500' : 'bg-yellow-500' }}">
              {{ ucfirst($pedido->estado_pago) }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- End Card Pago -->
  </div>
  <!-- End Grid -->

  <div class="flex flex-col md:flex-row gap-4 mt-4">
    <div class="md:w-3/4">
      <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left font-semibold">Producto</th>
              <th class="text-left font-semibold">Precio</th>
              <th class="text-left font-semibold">Cantidad</th>
              <th class="text-left font-semibold">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pedido->productos as $item)
            <tr>
              <td class="py-4">
                <div class="flex items-center">
                  <img class="h-16 w-16 mr-4 object-cover rounded-lg bg-gray-100" 
                    src="{{ url('storage/products/' . ($item->producto->imagenes[0] ?? '')) }}" 
                    alt="{{ $item->producto->nombre }}">
                  <span class="font-semibold">{{ $item->producto->nombre }}</span>
                </div>
              </td>
              <td class="py-4">RD$ {{ number_format($item->precio_unitario, 2) }}</td>
              <td class="py-4">
                <span class="text-center w-8">{{ $item->cantidad }}</span>
              </td>
              <td class="py-4">RD$ {{ number_format($item->precio_total, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
        <h1 class="font-3xl font-bold text-slate-500 mb-3">Dirección de Envío</h1>
        @if($pedido->direccion)
        <div class="flex justify-between items-center">
          <div>
            <p>{{ $pedido->direccion->direccion_calle }}, {{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}, {{ $pedido->direccion->codigo_postal }}</p>
          </div>
          <div>
            <p class="font-semibold">Teléfono:</p>
            <p>{{ $pedido->direccion->telefono }}</p>
          </div>
        </div>
        @else
        <div class="text-gray-500">No hay dirección asociada a este pedido.</div>
        @endif
      </div>
    </div>
    <div class="md:w-1/4">
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4">Resumen</h2>
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span>RD$ {{ number_format($pedido->productos->sum('precio_total'), 2) }}</span>
        </div>
        <div class="flex justify-between mb-2">
          <span>ITBIS (18%)</span>
          <span>RD$ {{ number_format($pedido->productos->sum('precio_total') * 0.18, 2) }}</span>
        </div>
        <div class="flex justify-between mb-2">
          <span>Envío</span>
          <span>RD$ {{ number_format($pedido->costo_envio ?? 0, 2) }}</span>
        </div>
        <hr class="my-2">
        <div class="flex justify-between mb-2">
          <span class="font-semibold">Total</span>
          <span class="font-semibold">RD$ {{ number_format(
            $pedido->productos->sum('precio_total') + 
            ($pedido->productos->sum('precio_total') * 0.18) + 
            ($pedido->costo_envio ?? 0), 
            2) 
          }}</span>
        </div>
        <hr class="my-4">
        <form method="POST" action="{{ route('factura.pdf', $pedido->id) }}" class="w-full">
          @csrf
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Descargar Factura
          </button>
        </form>
      </div>

      <!-- Dirección de envío -->
      <div class="bg-white rounded-lg shadow-md p-6 mt-4">
        <h2 class="text-lg font-semibold mb-4">Dirección de Envío</h2>
        @if($pedido->direccion)
          <div class="space-y-2 text-gray-600">
            <p class="font-medium text-gray-800">{{ $pedido->user->name }}</p>
            <p>{{ $pedido->direccion->direccion_calle }}</p>
            <p>{{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}</p>
            <p>{{ $pedido->direccion->codigo_postal }}</p>
            <div class="pt-2 border-t border-gray-200 mt-2">
    </div>
  </div>
</div>