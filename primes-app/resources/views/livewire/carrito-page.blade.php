<div class="min-h-screen bg-black">
  <section class="py-10 rounded-lg">
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 mx-auto">
      <div class="mb-10">
        <h2 class="text-3xl font-bold text-center text-white mb-6 cyber-glitch-text">
          Carrito de Compras
        </h2>
        <p class="text-center text-blue-400 max-w-3xl mx-auto">Revisa y gestiona tus productos seleccionados</p>
      </div>
      
      <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-3/4">
          <div class="bg-gray-900/80 overflow-x-auto rounded-xl shadow-md p-6 mb-4 border border-blue-500/20">
            <table class="w-full">
              <thead>
                <tr class="border-b border-blue-500/20">
                  <th class="text-left font-semibold text-white py-4">Producto</th>
                  <th class="text-left font-semibold text-white py-4">Precio</th>
                  <th class="text-left font-semibold text-white py-4">Cantidad</th>
                  <th class="text-left font-semibold text-white py-4">Total</th>
                  <th class="text-left font-semibold text-white py-4">Eliminar</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-blue-500/10">
                  <td class="py-4">
                    <div class="flex items-center">
                      <img class="h-16 w-16 mr-4 rounded-lg object-cover" src="https://via.placeholder.com/150" alt="Product image">
                      <span class="font-semibold text-white">Product name</span>
                    </div>
                  </td>
                  <td class="py-4 text-blue-400">RD$ 19.99</td>
                  <td class="py-4">
                    <div class="flex items-center">
                      <button class="border border-blue-500/30 rounded-md py-1 px-3 mr-2 text-blue-400 hover:bg-blue-500/20 transition-colors">-</button>
                      <span class="text-center w-8 text-white">1</span>
                      <button class="border border-blue-500/30 rounded-md py-1 px-3 ml-2 text-blue-400 hover:bg-blue-500/20 transition-colors">+</button>
                    </div>
                  </td>
                  <td class="py-4 text-blue-400">RD$ 19.99</td>
                  <td>
                    <button class="bg-gray-800 border border-blue-500/30 rounded-lg px-3 py-1 text-white hover:bg-red-900/50 hover:border-red-500/50 transition-colors">Eliminar</button>
                  </td>
                </tr>
                <!-- More product rows -->
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="md:w-1/4">
          <div class="bg-gray-900/80 rounded-xl shadow-md p-6 border border-blue-500/20">
            <h2 class="text-xl font-semibold mb-6 text-white border-b border-blue-500/20 pb-2">Resumen</h2>
            <div class="flex justify-between mb-3">
              <span class="text-gray-300">Subtotal</span>
              <span class="text-blue-400">RD$ 19.99</span>
            </div>
            <div class="flex justify-between mb-3">
              <span class="text-gray-300">Impuestos</span>
              <span class="text-blue-400">RD$ 1.99</span>
            </div>
            <div class="flex justify-between mb-4">
              <span class="text-gray-300">Envío</span>
              <span class="text-blue-400">RD$ 0.00</span>
            </div>
            <div class="border-t border-blue-500/20 my-4"></div>
            <div class="flex justify-between mb-6">
              <span class="font-semibold text-white">Total</span>
              <span class="font-semibold text-blue-400">RD$ 21.98</span>
            </div>
            <a href="/checkout">
              <button class="cyber-button w-full py-3 px-4 rounded-lg bg-blue-600/50 text-white border border-blue-500 hover:bg-blue-700/50 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg hover:shadow-blue-500/30">
                Finalizar Compra
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CSS para efectos cyber -->
  <style>
    .cyber-button {
      background: linear-gradient(45deg, rgba(37, 99, 235, 0.3), rgba(59, 130, 246, 0.3));
      position: relative;
      overflow: hidden;
    }
    
    .cyber-button:before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.3), transparent);
      transform: rotate(45deg);
      animation: shine 3s infinite;
    }
    
    @keyframes shine {
      0% {
        left: -200%;
        top: -50%;
      }
      100% {
        left: 100%;
        top: 100%;
      }
    }
    
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