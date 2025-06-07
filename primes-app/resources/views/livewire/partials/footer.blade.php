<footer class="bg-black border-t border-blue-500/20 backdrop-blur-sm">
  <div class="w-full max-w-7xl py-10 px-4 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
    <!-- Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
      <div class="col-span-full lg:col-span-1">
        <a class="flex-none text-2xl font-bold text-white cyber-glitch-text" href="/" aria-label="Brand">
          TECNO<span class="text-blue-500">BOX</span>
        </a>
        <p class="mt-4 text-gray-400 text-sm">
          Tu destino gaming definitivo para hardware de alta gama y periféricos gaming.
        </p>
      </div>
      <!-- End Col -->

      <div class="col-span-1">
        <h4 class="font-semibold text-blue-400 cyber-glitch-text">Productos</h4>

        <div class="mt-3 grid space-y-3">
          <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-blue-500 transition-colors" href="/categories">Categorías</a></p>
          <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-blue-500 transition-colors" href="/products">Todos los productos</a></p>
          <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-blue-500 transition-colors" href="/products">Productos populares</a></p>
        </div>
      </div>
      <!-- End Col -->

      <div class="col-span-1">
        
        <div class="mt-3 grid space-y-3">
          <!-- Eliminado: Sobre nosotros, Blog, Clientes -->
        </div>
      </div>
      <!-- End Col -->

      <div class="col-span-2">
        <h4 class="font-semibold text-blue-400 cyber-glitch-text">Mantente actualizado</h4>
        <form id="newsletter-form" onsubmit="event.preventDefault(); document.getElementById('newsletter-alert').classList.remove('hidden');">
          <div class="mt-4 flex flex-col items-center gap-2 sm:flex-row sm:gap-3 bg-gray-900/50 rounded-lg p-2 border border-blue-500/20">
            <div class="w-full">
              <input type="email" id="newsletter-email" name="newsletter-email" class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-black/50 text-gray-400" placeholder="Ingresa tu email" required>
            </div>
            <button type="submit" class="w-full sm:w-auto whitespace-nowrap p-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-blue-500/20 bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 hover:border-blue-500/30 transition-all duration-200">
              Suscribirse
            </button>
          </div>
          <div id="newsletter-alert" class="hidden mt-3 p-3 bg-green-600/10 border border-green-600/20 text-green-400 rounded-lg text-center">
            ¡Gracias! Te mantendremos actualizado con nuestras novedades.
          </div>
        </form>
      </div>
      <!-- End Col -->
    </div>
    <!-- End Grid -->

    <div class="mt-5 sm:mt-12 grid gap-y-2 sm:gap-y-0 sm:flex sm:justify-between sm:items-center">
      <div class="flex justify-between items-center">
        <p class="text-sm text-gray-400">© 2024 TECNOBOX. Todos los derechos reservados.</p>
      </div>
      <!-- End Col -->

      <!-- Social Brands -->
      <div>
        <a class="w-10 h-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-blue-500/20 text-blue-400 hover:bg-blue-500/10 hover:border-blue-500/30 transition-all duration-200" href="https://github.com/FranklinJFrame" target="_blank" rel="noopener">
          <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
          </svg>
        </a>
      </div>
      <!-- End Social Brands -->
    </div>
  </div>
</footer>