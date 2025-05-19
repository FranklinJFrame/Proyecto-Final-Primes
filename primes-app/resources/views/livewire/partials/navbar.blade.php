<header class="sticky top-0 z-50 w-full bg-black/95 backdrop-blur-sm border-b border-blue-500/20">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
              <a wire:navigate href="/" class="text-2xl font-bold text-white cyber-glitch-text">
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
                  @livewire('cart-counter', [], key('cart-counter-navbar'))
                  Carrito
              </a>
              @php $user = auth()->user(); @endphp
              @if($user)
                  <div class="relative user-dropdown-group">
                      <button class="flex items-center text-gray-300 hover:text-blue-500 transition-colors focus:outline-none">
                          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          <span class="font-semibold">{{ $user->name }}</span>
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                      </button>
                      <div class="absolute right-0 mt-2 w-48 bg-black border border-blue-500/20 rounded-lg shadow-lg py-2 user-dropdown-menu z-50">
                          <a href="/my-orders" class="block px-4 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Mis pedidos</a>
                          <a href="/settings/profile" class="block px-4 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Cuenta</a>
                          <form method="POST" action="/logout">
                              @csrf
                              <button type="submit" class="w-full text-left px-4 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Logout</button>
                          </form>
                      </div>
                  </div>
              @else
                  <a wire:navigate href="/login" class="flex items-center text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('login*') ? 'text-blue-500' : '' }}">
                      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      Login
                  </a>
              @endif
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
                  @livewire('cart-counter', [], key('cart-counter-navbar'))
                  Carrito
              </a>
              @if($user)
                  <div class="border-t border-blue-500/10 my-2"></div>
                  <div class="px-3 py-2 text-gray-300 font-semibold flex items-center">
                      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      {{ $user->name }}
                  </div>
                  <a href="/my-orders" class="block px-3 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Mis pedidos</a>
                  <a href="/settings/profile" class="block px-3 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Cuenta</a>
                  <form method="POST" action="/logout">
                      @csrf
                      <button type="submit" class="w-full text-left px-3 py-2 text-gray-300 hover:bg-blue-500/10 hover:text-blue-500 transition-colors">Logout</button>
                  </form>
              @else
                  <a wire:navigate href="/login" class="flex items-center px-3 py-2 text-gray-300 hover:text-blue-500 transition-colors {{ request()->is('login*') ? 'text-blue-500' : '' }}">
                      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      Login
                  </a>
              @endif
          </div>
      </div>
      <script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
  </nav>
  <style>
      .user-dropdown-group:hover .user-dropdown-menu,
      .user-dropdown-group:focus-within .user-dropdown-menu {
          display: block !important;
      }
      .user-dropdown-menu {
          display: none;
      }
  </style>
</header>

