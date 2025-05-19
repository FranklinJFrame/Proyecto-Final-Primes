<div class="min-h-screen bg-gray-100 py-10">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
    <!-- Productos en el carrito -->
    <div class="md:w-2/3 w-full flex flex-col gap-6">
      <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
        Carrito de Compras
      </h2>
      @if($carrito->isEmpty())
        <div class="text-center text-gray-500 text-xl py-24 font-semibold">Tu carrito está vacío.</div>
      @else
      <div class="flex flex-col gap-6">
        @foreach($carrito as $item)
        <div class="flex flex-col md:flex-row items-center bg-white rounded-xl shadow border border-gray-200 p-6 gap-6 hover:shadow-md transition-all duration-200">
          <img class="w-28 h-28 object-contain rounded-lg border border-gray-200 bg-gray-50" src="{{ $item->producto->imagenes[0] ? url('storage', $item->producto->imagenes[0]) : 'https://via.placeholder.com/150' }}" alt="{{ $item->producto->nombre }}">
          <div class="flex-1 flex flex-col gap-2">
            <span class="text-lg font-semibold text-gray-800 mb-1">{{ $item->producto->nombre }}</span>
            <span class="text-base text-gray-600 font-medium">RD$ {{ number_format($item->precio_unitario, 2) }}</span>
            <div class="flex items-center gap-2 mt-2">
              <button wire:click="decrement({{ $item->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition text-lg font-bold">-</button>
              <span class="inline-block min-w-[2.5rem] text-base text-gray-800 font-bold px-3 py-1 bg-gray-100 rounded border border-gray-200 text-center">{{ $item->cantidad }}</span>
              <button wire:click="increment({{ $item->id }})" class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition text-lg font-bold">+</button>
            </div>
          </div>
          <div class="flex flex-col items-center gap-2">
            <span class="text-base text-gray-700 font-semibold bg-gray-100 px-4 py-2 rounded border border-gray-200">Total: RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</span>
            <button wire:click="remove({{ $item->id }})" class="w-9 h-9 flex items-center justify-center bg-gray-200 text-red-500 rounded-full hover:bg-red-100 transition-all duration-200" title="Eliminar">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-8 flex justify-end">
        <button wire:click="clear" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow hover:bg-gray-300 transition-all">Vaciar carrito</button>
      </div>
      @endif
    </div>
    <!-- Resumen de compra -->
    <div class="md:w-1/3 w-full">
      <div class="bg-white rounded-xl shadow border border-gray-200 p-8 sticky top-10">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b border-gray-200 pb-2 flex items-center gap-2">
          <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
          Resumen
        </h3>
        <div class="flex justify-between mb-3 text-base">
          <span class="text-gray-500">Subtotal</span>
          <span class="text-gray-800 font-semibold">RD$ {{ number_format($total, 2) }}</span>
        </div>
        <div class="flex justify-between mb-3 text-base">
          <span class="text-gray-500">Impuestos</span>
          <span class="text-gray-800 font-semibold">RD$ 0.00</span>
        </div>
        <div class="flex justify-between mb-4 text-base">
          <span class="text-gray-500">Envío</span>
          <span class="text-gray-800 font-semibold">RD$ 0.00</span>
        </div>
        <div class="border-t border-gray-200 my-4"></div>
        <div class="flex justify-between mb-6 text-lg font-bold">
          <span class="text-gray-800">Total</span>
          <span class="text-blue-600">RD$ {{ number_format($total, 2) }}</span>
        </div>
        <a href="/checkout">
          <button class="w-full py-4 rounded-lg bg-blue-600 text-lg font-bold text-white hover:bg-blue-700 transition-all shadow">Finalizar Compra</button>
        </a>
      </div>
    </div>
  </div>
</div>     