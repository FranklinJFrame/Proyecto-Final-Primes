<div class="min-h-screen bg-black">
  <section class="py-10 rounded-lg">
    <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
      <div class="mb-16">
        <h2 class="text-3xl font-bold text-center text-white mb-6 cyber-glitch-text">
          Productos Gaming
        </h2>
        <p class="text-center text-blue-400 max-w-3xl mx-auto">Explora nuestra colección de tecnología gaming de última generación</p>
      </div>
      
      <div class="flex flex-wrap -mx-3">
        <!-- Sidebar con filtros -->
        <div class="w-full pr-2 lg:w-1/4 lg:block">
          <div class="mb-5">
            <input type="text" wire:model.live="search" placeholder="Buscar productos..." class="w-full px-4 py-2 rounded-lg bg-gray-900 text-blue-400 border border-blue-500/40 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-blue-300 shadow-md" />
          </div>
          <div class="p-4 mb-5 bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl">
            <h2 class="text-2xl font-bold text-white"> Categorías</h2>
            <div class="w-16 pb-2 mb-6 border-b border-blue-500"></div>
            <ul>
              @foreach ($categorias as $categoria)
              <li class="mb-4" wire:key="{{ $categoria->id }}">
                <label for="{{ $categoria->slug }}" class="flex items-center text-gray-300 hover:text-blue-400 transition-colors">
                  <input type="checkbox" wire:model.live="selected_categorias" id="{{ $categoria->slug }}" value="{{ $categoria->id }}" class="w-4 h-4 mr-2 accent-blue-500">
                  <span class="text-lg">{{ $categoria->nombre }}</span>
                </label>
              </li>
              @endforeach
            </ul>
          </div>
          
          <div class="p-4 mb-5 bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl">
            <h2 class="text-2xl font-bold text-white">Marcas</h2>
            <div class="w-16 pb-2 mb-6 border-b border-blue-500"></div>
            <ul>
              @foreach ($marcas as $marca)
              <li class="mb-4" wire:key="{{ $marca->id }}">
                <label for="marca-{{ $marca->slug }}" class="flex items-center text-gray-300 hover:text-blue-400 transition-colors">
                  <input type="checkbox" wire:model.live="selected_marcas" id="marca-{{ $marca->slug }}" value="{{ $marca->id }}" class="w-4 h-4 mr-2 accent-blue-500">
                  <span class="text-lg">{{ $marca->nombre }}</span>
                </label>
              </li>
              @endforeach
            </ul>
          </div>
          
          <div class="p-4 mb-5 bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl">
            <h2 class="text-2xl font-bold text-white">Estado</h2>
            <div class="w-16 pb-2 mb-6 border-b border-blue-500"></div>
            <ul>
              <li class="mb-4">
                <label class="flex items-center text-gray-300 hover:text-blue-400 transition-colors">
                  <input type="checkbox" wire:model.live="selected_estado" value="en_stock" class="w-4 h-4 mr-2 accent-blue-500">
                  <span class="text-lg">En Stock</span>
                </label>
              </li>
              <li class="mb-4">
                <label class="flex items-center text-gray-300 hover:text-blue-400 transition-colors">
                  <input type="checkbox" wire:model.live="selected_estado" value="en_oferta" class="w-4 h-4 mr-2 accent-blue-500">
                  <span class="text-lg">En Oferta</span>
                </label>
              </li>
            </ul>
          </div>

          <div class="p-4 mb-5 bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl">
            <h2 class="text-2xl font-bold text-white ">Precio</h2>
            <div class="w-16 pb-2 mb-6 border-b border-blue-500"></div>
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-2">
                <span class="text-blue-400 font-bold">RD$</span>
                <input type="number" wire:model.live="precio_min" min="1000" max="precio_max" class="w-20 px-2 py-1 rounded bg-gray-800 text-blue-400 border border-blue-500/30 focus:outline-none" placeholder="Mínimo">
                <span class="text-blue-400">-</span>
                <input type="number" wire:model.live="precio_max" min="precio_min" max="500000" class="w-20 px-2 py-1 rounded bg-gray-800 text-blue-400 border border-blue-500/30 focus:outline-none" placeholder="Máximo">
              </div>
              <input type="range" wire:model.live="precio_max" min="1000" max="500000" step="1000" class="w-full h-1 bg-blue-400/30 rounded-lg appearance-none cursor-pointer accent-blue-500">
              <div class="text-right text-blue-400 font-bold text-lg mb-4">
                RD$ <span>{{ number_format($precio_max, 0) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="inline-block text-lg font-bold text-blue-400">RD$ 1000</span>
                <span class="inline-block text-lg font-bold text-blue-400">RD$ 500000</span>
              </div>
            </div>
          </div>
          <button wire:click="$set('selected_categorias', []); $set('selected_marcas', []); $set('selected_estado', []); $set('precio_min', 1000); $set('precio_max', 500000);" class="w-full mt-2 py-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-bold rounded-lg shadow hover:from-cyan-400 hover:to-blue-500 transition-all">Limpiar filtros</button>
        </div>
        
        <!-- Contenido principal -->
        <div class="w-full px-3 lg:w-3/4">
          <div class="px-3 mb-6">
            <div class="flex items-center justify-between px-4 py-3 bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl">
              <div class="flex items-center">
                <select wire:model.live="ordenarPor" class="block w-64 text-base bg-gray-800 cursor-pointer text-white border border-blue-500/30 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition-colors">
                  <option value="relevancia">Ordenar por más relevante</option>
                  <option value="recientes">Ordenar por más recientes</option>
                  <option value="precio_asc">Ordenar por precio: menor a mayor</option>
                  <option value="precio_desc">Ordenar por precio: mayor a menor</option>
                </select>
              </div>
            </div>
          </div>
          <div class="flex flex-wrap items-center">
            <!-- Productos -->
            @if($productos->isEmpty())
              <div class="w-full text-center p-12">
                <p class="text-white text-xl">No hay productos disponibles en este momento.</p>
              </div>
            @else
              @foreach ($productos as $producto)
                <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key="producto-{{ $producto->id }}">
                  <div class="cyber-product-card bg-gray-900/80 border border-blue-500/20 rounded-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:shadow-blue-500/20">
                    <div class="relative bg-gray-800">
                      <a href="/products/{{ $producto->slug }}">
                        <img src="{{ url('storage', $producto->imagenes[0]) }}" alt="{{ $producto->nombre }}" loading="lazy" class="object-cover w-full h-56 mx-auto transform hover:scale-105 transition-all duration-500">
                      </a>
                    </div>
                    <div class="p-4">
                      <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-medium text-white">
                          {{ $producto->nombre }}
                        </h3>
                      </div>
                      <p class="text-gray-300 text-sm mb-2 line-clamp-2">{{ $producto->descripcion }}</p>
                      <p class="text-lg">
                        <span class="text-blue-400 font-bold">RD$ {{ number_format($producto->precio, 2) }}</span>
                      </p>
                    </div>
                    <div class="flex justify-center p-4 border-t border-blue-500/20">
                      @livewire('add-to-cart-button', ['productoId' => $producto->id], key('add-to-cart-'.$producto->id))
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
          
          <!-- Paginación con estilo cyber -->
          <div class="flex justify-end mt-10">
            {{ $productos->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- CSS para efectos cyber -->
  <style>
    .cyber-button-small {
      background: linear-gradient(45deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));
      border: 1px solid rgba(79, 172, 254, 0.3);
      transition: all 0.3s ease;
    }
    
    .cyber-button-small:hover {
      border-color: rgba(79, 172, 254, 0.5);
      box-shadow: 0 0 15px rgba(79, 172, 254, 0.3);
    }
    
    .cyber-product-card {
      transition: all 0.3s ease;
    }
    
    .cyber-product-card:hover {
      border-color: rgba(59, 130, 246, 0.5);
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
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
    
    .glow-blue-sm {
      box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    }
  </style>
</div>