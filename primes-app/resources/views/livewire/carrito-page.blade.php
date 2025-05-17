<div class="min-h-screen bg-black py-10">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
    <!-- Productos en el carrito -->
    <div class="md:w-2/3 w-full flex flex-col gap-6">
      <h2 class="text-3xl font-bold text-white mb-4 cyber-glitch-text">Carrito de Compras</h2>
      <div class="flex flex-col gap-6">
        <!-- Ejemplo de producto, reemplaza con tu loop -->
        <div class="flex flex-col md:flex-row items-center bg-gray-900/80 rounded-2xl shadow-lg border border-blue-500/20 p-6 gap-6">
          <img class="w-32 h-32 object-contain rounded-xl border border-blue-500/10 bg-gray-800" src="https://via.placeholder.com/150" alt="Product image">
          <div class="flex-1 flex flex-col gap-2">
            <span class="text-xl font-bold text-white">Product name</span>
            <span class="text-lg text-blue-400 font-semibold">RD$ 19.99</span>
            <div class="flex items-center gap-2 mt-2">
              <button class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded hover:bg-blue-500/40 transition">-</button>
              <span class="text-lg text-white font-bold px-3">1</span>
              <button class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded hover:bg-blue-500/40 transition">+</button>
            </div>
          </div>
          <div class="flex flex-col items-center gap-2">
            <span class="text-lg text-blue-400 font-semibold">Total: RD$ 19.99</span>
            <button class="w-10 h-10 flex items-center justify-center bg-red-500/20 text-red-400 rounded-full hover:bg-red-500/40 transition" title="Eliminar">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <!-- Fin ejemplo de producto -->
      </div>
    </div>
    <!-- Resumen de compra -->
    <div class="md:w-1/3 w-full">
      <div class="bg-gray-900/90 rounded-2xl shadow-xl border border-blue-500/20 p-8 sticky top-10">
        <h3 class="text-2xl font-bold text-white mb-6 border-b border-blue-500/20 pb-2">Resumen</h3>
        <div class="flex justify-between mb-3 text-lg">
          <span class="text-gray-300">Subtotal</span>
          <span class="text-blue-400 font-semibold">RD$ 19.99</span>
        </div>
        <div class="flex justify-between mb-3 text-lg">
          <span class="text-gray-300">Impuestos</span>
          <span class="text-blue-400 font-semibold">RD$ 1.99</span>
        </div>
        <div class="flex justify-between mb-4 text-lg">
          <span class="text-gray-300">Envío</span>
          <span class="text-blue-400 font-semibold">RD$ 0.00</span>
        </div>
        <div class="border-t border-blue-500/20 my-4"></div>
        <div class="flex justify-between mb-6 text-xl font-bold">
          <span class="text-white">Total</span>
          <span class="text-blue-400">RD$ 21.98</span>
        </div>
        <a href="/checkout">
          <button class="w-full py-4 rounded-lg bg-blue-600 text-xl font-bold text-white hover:bg-blue-700 transition-all shadow-lg">Finalizar Compra</button>
        </a>
      </div>
    </div>
  </div>
  <style>
    .cyber-glitch-text {
      position: relative;
      text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                  -0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
                  0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
      animation: glitch 500ms infinite;
    }
    @keyframes glitch {
      0% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                    -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
      }
      14% {
        text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                    -0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
      }
      15% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                    0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
      }
      49% {
        text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
                    0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
      }
      50% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                    0.05em 0 0 rgba(0, 255, 0, 0.75),
                    0 -0.05em 0 rgba(0, 0, 255, 0.75);
      }
      99% {
        text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
                    0.05em 0 0 rgba(0, 255, 0, 0.75),
                    0 -0.05em 0 rgba(0, 0, 255, 0.75);
      }
      100% {
        text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75),
                    -0.025em -0.025em 0 rgba(0, 255, 0, 0.75),
                    -0.025em -0.05em 0 rgba(0, 0, 255, 0.75);
      }
    }
  </style>
</div>     