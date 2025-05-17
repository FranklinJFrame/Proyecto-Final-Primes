<div class="min-h-screen bg-black">
  <div class="relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
      <!-- Gradiente animado de fondo -->
      <div class="absolute inset-0 bg-gradient-radial"></div>
      <!-- Grid animada -->
      <div class="absolute inset-0 bg-grid-pattern opacity-20 animate-grid"></div>
      <!-- Líneas de neón -->
      <div class="absolute inset-0 neon-lines"></div>
    </div>

    <div class="relative w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
      <section class="overflow-hidden bg-gray-900/80 backdrop-blur-md border border-blue-500/20 rounded-xl py-11 font-poppins">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
          
          <div class="flex flex-wrap -mx-4">
            <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ $producto->imagenes[0] }}' }">
              <div class="sticky top-0 z-50 overflow-hidden">
                <div class="relative mb-6 lg:mb-10 lg:h-2/4 cyber-card p-2">
                  <img x-bind:src="mainImage" alt="" class="object-cover w-full lg:h-full rounded-lg">
                </div>
                <div class="flex-wrap hidden md:flex">
                  @foreach ($producto->imagenes as $imagen)
                    <div class="w-1/2 p-2 sm:w-1/4" x-on:click="mainImage=url('storage', {{ $imagen }})">
                      <img src="url('storage', {{ $imagen }})" alt="{{$producto->nombre}}" 
                           class="object-cover w-full lg:h-20 cursor-pointer rounded-lg hover:border-2 hover:border-blue-500 transition-all duration-300">
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="w-full px-4 md:w-1/2">
              <div class="lg:pl-20">
                <div class="mb-8 [&>ul]:list-disc [&>ul]:ml-4">
                  <h2 class="max-w-xl mb-6 text-2xl font-bold text-white cyber-glitch-text md:text-4xl">
                    {{ $producto->nombre }}
                  </h2>
                  <p class="inline-block mb-6 text-4xl font-bold text-blue-400">
                    <span>{{ Number::currency($producto->precio, 'USD') }}</span>
                  </p>
                  <div class="max-w-md text-gray-300 prose prose-invert">
                    {!! Str::markdown($producto->descripcion) !!}
                  </div>
                </div>

                <div class="w-32 mb-8">
                  <label class="w-full pb-1 text-xl font-semibold text-white border-b border-blue-500">
                    Cantidad
                  </label>
                  <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                    <button class="w-20 h-full text-white bg-blue-500/20 rounded-l hover:bg-blue-500/40 transition-colors">
                      <span class="m-auto text-2xl font-thin">-</span>
                    </button>
                    <input type="number" readonly class="flex items-center w-full font-semibold text-center text-white bg-gray-800 outline-none focus:outline-none text-md" placeholder="1">
                    <button class="w-20 h-full text-white bg-blue-500/20 rounded-r hover:bg-blue-500/40 transition-colors">
                      <span class="m-auto text-2xl font-thin">+</span>
                    </button>
                    
                  </div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                  <button class="w-full p-4 bg-blue-500 rounded-md lg:w-2/5 text-white hover:bg-blue-600 transition-colors cyber-button">
                    Agregar al carrito
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>