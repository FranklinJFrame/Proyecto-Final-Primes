<div class="min-h-screen bg-black py-10">
  <div class="max-w-6xl mx-auto bg-gray-900/90 rounded-2xl shadow-xl flex flex-col md:flex-row overflow-hidden border border-blue-500/20">
    <!-- Imagen principal y miniaturas -->
    <div class="md:w-1/2 flex flex-col items-center justify-center p-8 bg-gray-900">
      <img :src="mainImage" src="{{ url('storage/'.$producto->imagenes[0]) }}" alt="{{ $producto->nombre }}" class="w-full max-w-md h-80 object-contain rounded-xl border border-blue-500/10 bg-gray-800 mb-4" x-data="{ mainImage: '{{ url('storage/'.$producto->imagenes[0]) }}' }">
      <div class="flex gap-2 mt-2">
        @foreach ($producto->imagenes as $imagen)
          <img src="{{ url('storage/'.$imagen) }}" alt="Miniatura" class="w-16 h-16 object-contain rounded-lg border border-blue-500/10 cursor-pointer hover:border-blue-500 transition-all duration-200" @click="mainImage='{{ url('storage/'.$imagen) }}'">
        @endforeach
      </div>
    </div>
    <!-- Info producto -->
    <div class="md:w-1/2 flex flex-col justify-between p-8">
      <div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $producto->nombre }}</h1>
        <div class="text-2xl md:text-3xl font-bold text-blue-400 mb-6">{{ Number::currency($producto->precio, 'USD') }}</div>
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-blue-400 mb-2">Especificaciones</h2>
          <ul class="bg-gray-800/80 rounded-xl p-4 max-h-64 overflow-y-auto text-gray-200 text-base space-y-2 border border-blue-500/10">
            @foreach (explode("\n", trim(strip_tags($producto->descripcion))) as $linea)
              @if (trim($linea) !== '')
                <li class="flex items-start gap-2">
                  <span class="mt-1 w-2 h-2 bg-blue-400 rounded-full inline-block"></span>
                  <span>{{ $linea }}</span>
                </li>
              @endif
            @endforeach
          </ul>
        </div>
      </div>
      <div class="mt-8 flex flex-col gap-4">
        <div class="flex items-center gap-4">
          <label class="text-white font-semibold">Cantidad:</label>
          <input type="number" min="1" value="1" class="w-20 px-2 py-1 rounded bg-gray-800 text-white border border-blue-500/20 focus:border-blue-500 outline-none">
        </div>
        <button class="w-full py-4 bg-blue-500 rounded-lg text-xl font-bold text-white hover:bg-blue-600 transition-colors shadow-lg">Agregar al carrito</button>
      </div>
    </div>
  </div>
  <style>
    .bg-gray-800/80::-webkit-scrollbar {
      width: 8px;
    }
    .bg-gray-800/80::-webkit-scrollbar-thumb {
      background: #3b82f6;
      border-radius: 4px;
    }
  </style>
</div>