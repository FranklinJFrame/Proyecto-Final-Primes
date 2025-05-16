<header class="sticky top-0 z-50 w-full bg-black/95 backdrop-blur-sm border-b border-blue-500/20">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
              <a href="/" class="text-2xl font-bold text-white cyber-glitch-text">
                  TECNO<span class="text-blue-500">BOX</span>
              </a>
          </div>

          <!-- Menú principal -->
          <div class="hidden md:flex md:items-center md:space-x-8">
              <a wire:navigate href="/" class="text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('/') ? 'text-blue-500' : '' }}">
                  Home
              </a>
              <a wire:navigate href="/categories" class="text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('categories*') ? 'text-blue-500' : '' }}">
                  Categorías
              </a>
              <a wire:navigate href="/products" class="text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('products*') ? 'text-blue-500' : '' }}">
                  Productos
              </a>
              <a wire:navigate href="/cart" class="flex items-center text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('cart*') ? 'text-blue-500' : '' }}">
                  <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  Carrito
              </a>
          </div>

          <!-- Botón menú móvil -->
          <div class="md:hidden">
              <button type="button" class="text-gray-300 hover:text-white p-2" data-hs-collapse="#navbar-collapse-with-animation">
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
              </button>
          </div>
      </div>

      <!-- Menú móvil expandido -->
      <div class="md:hidden">
          <div id="navbar-collapse-with-animation" class="hidden px-2 pt-2 pb-3 space-y-1">
              <a wire:navigate href="/" class="block px-3 py-2 text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('/') ? 'text-blue-500' : '' }}">
                  Home
              </a>
              <a wire:navigate href="/categories" class="block px-3 py-2 text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('categories*') ? 'text-blue-500' : '' }}">
                  Categorías
              </a>
              <a wire:navigate href="/products" class="block px-3 py-2 text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('products*') ? 'text-blue-500' : '' }}">
                  Productos
              </a>
              <a wire:navigate href="/cart" class="flex items-center px-3 py-2 text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('cart*') ? 'text-blue-500' : '' }}">
                  <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  Carrito
              </a>
          </div>
      </div>
  </nav>
</header>