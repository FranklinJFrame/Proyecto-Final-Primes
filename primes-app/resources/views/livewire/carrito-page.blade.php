<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-10">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8 px-4">
    <!-- Productos en el carrito -->
    <div class="md:w-2/3 w-full flex flex-col gap-6">
      <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 mb-6 flex items-center gap-3">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
        Carrito de Compras
      </h2>
      @if($carrito->isEmpty())
        <div class="text-center text-gray-400 text-2xl py-24 font-semibold bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-blue-500/20 shadow-xl">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          Tu carrito está vacío.
        </div>
      @else
      <div class="flex flex-col gap-6">
        @foreach($carrito as $item)
        <div class="flex flex-col md:flex-row items-center bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-blue-500/20 p-6 gap-6 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300">
          <img class="w-32 h-32 object-contain rounded-xl border border-blue-500/20 bg-gray-700/50 transform transition-all duration-300 hover:scale-105" 
               src="{{ $item->producto->imagenes[0] ? url('storage', $item->producto->imagenes[0]) : 'https://via.placeholder.com/150' }}" 
               alt="{{ $item->producto->nombre }}">
          <div class="flex-1 flex flex-col gap-3">
            <span class="text-xl font-semibold text-white mb-1">{{ $item->producto->nombre }}</span>
            <span class="text-lg text-blue-400 font-medium">RD$ {{ number_format($item->precio_unitario, 2) }}</span>
            <div class="flex items-center gap-3 mt-2">
              <button wire:click="decrement({{ $item->id }})" class="w-10 h-10 flex items-center justify-center bg-gray-700/50 text-gray-300 rounded-xl hover:bg-gray-700 transition-all duration-300 text-xl font-bold hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
              </button>
              <span class="inline-block min-w-[3rem] text-lg text-white font-bold px-4 py-2 bg-gray-700/50 rounded-xl border border-blue-500/20 text-center">{{ $item->cantidad }}</span>
              <button wire:click="increment({{ $item->id }})" class="w-10 h-10 flex items-center justify-center bg-gray-700/50 text-gray-300 rounded-xl hover:bg-gray-700 transition-all duration-300 text-xl font-bold hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-center gap-3">
            <span class="text-lg text-white font-semibold bg-gray-700/50 px-6 py-3 rounded-xl border border-blue-500/20">Total: RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</span>
            <button wire:click="remove({{ $item->id }})" class="w-12 h-12 flex items-center justify-center bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-all duration-300" title="Eliminar">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-8 flex justify-end">
        <button wire:click="clear" class="px-6 py-3 bg-red-500/20 text-red-400 font-semibold rounded-xl hover:bg-red-500/30 transition-all duration-300 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          Vaciar carrito
        </button>
      </div>
      @endif
    </div>
    <!-- Resumen de compra -->
    <div class="md:w-1/3 w-full">
      <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-blue-500/20 p-8 sticky top-10 shadow-xl hover:shadow-blue-500/10 transition-all duration-300">
        <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 mb-6 border-b border-blue-500/20 pb-4 flex items-center gap-2">
          <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
          Resumen
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center bg-gray-700/30 rounded-xl p-4">
            <span class="text-gray-400">Subtotal</span>
            <span class="text-white font-semibold">RD$ {{ number_format($total, 2) }}</span>
          </div>
          <div class="flex justify-between items-center bg-gray-700/30 rounded-xl p-4">
            <span class="text-gray-400">Impuestos</span>
            <span class="text-white font-semibold">RD$ 0.00</span>
          </div>
          <div class="flex justify-between items-center bg-gray-700/30 rounded-xl p-4">
            <span class="text-gray-400">Envío</span>
            <span class="text-white font-semibold">RD$ 0.00</span>
          </div>
        </div>
        <div class="border-t border-blue-500/20 my-6"></div>
        <div class="flex justify-between items-center mb-8">
          <span class="text-xl text-white font-bold">Total</span>
          <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600">RD$ {{ number_format($total, 2) }}</span>
        </div>
        <a href="/checkout">
          <button class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-lg font-bold text-white transition-all duration-300 transform hover:scale-105 shadow-lg">
            Finalizar Compra
          </button>
        </a>
      </div>
    </div>
  </div>
</div>