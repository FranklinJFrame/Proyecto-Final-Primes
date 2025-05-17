<div class="min-h-screen bg-black">
  <section class="py-10 rounded-lg">
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      <div class="mb-16">
        <h2 class="text-3xl font-bold text-center text-white mb-6 cyber-glitch-text">
          Categorías
        </h2>
        <p class="text-center text-blue-400 max-w-3xl mx-auto">Explora nuestra colección por categorías para encontrar la tecnología que necesitas</p>
      </div>
      
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 sm:gap-8">
        @forelse($categorias as $categoria)
        <a class="cyber-category-card group flex flex-col bg-gray-900/80 border border-blue-500/20 rounded-xl hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1" href="/categories/{{ $categoria->slug }}">
          <div class="p-5 md:p-6">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <img class="h-[5rem] w-[5rem] rounded-lg object-cover" src="{{ $categoria->imagen ? url('storage/'.$categoria->imagen) : 'https://via.placeholder.com/80x80?text=Sin+Imagen' }}" alt="{{ $categoria->nombre }}">
                <div class="ms-4">
                  <h3 class="group-hover:text-blue-400 text-2xl font-semibold text-white transition-colors duration-300">
                    {{ $categoria->nombre }}
                  </h3>
                </div>
              </div>
              <div class="ps-3">
                <svg class="flex-shrink-0 w-5 h-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m9 18 6-6-6-6" />
                </svg>
              </div>
            </div>
          </div>
        </a>
        @empty
        <div class="col-span-full text-center text-white text-xl py-12">No hay categorías disponibles en este momento.</div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- CSS para efectos cyber -->
  <style>
    .cyber-category-card {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }
    
    .cyber-category-card:hover {
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
  </style>
</div>