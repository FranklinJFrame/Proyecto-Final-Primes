<div class="min-h-screen bg-black py-10">
  <div class="max-w-6xl mx-auto">
    <a href="/products" class="inline-flex items-center mb-8 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-blue-600 transition font-semibold">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
      Volver a productos
    </a>
  </div>
  <div class="max-w-6xl mx-auto bg-gray-900/90 rounded-2xl shadow-xl flex flex-col md:flex-row overflow-hidden border border-blue-500/20">
    <!-- Imagen principal y miniaturas -->
    <div class="md:w-1/2 flex flex-col items-center justify-center p-8 bg-gray-900"
         x-data="{
           mainImage: '{{ url('storage/'.$producto->imagenes[0]) }}',
           zoom: false,
           zoomX: 0,
           zoomY: 0,
           setMain(img) { this.mainImage = img; },
           moveZoom(e) {
             const rect = e.target.getBoundingClientRect();
             this.zoomX = ((e.clientX - rect.left) / rect.width) * 100;
             this.zoomY = ((e.clientY - rect.top) / rect.height) * 100;
           }
         }"
    >
      <div class="relative w-full max-w-md h-80 mb-4 group">
        <img :src="mainImage" alt="{{ $producto->nombre }}" class="w-full h-80 object-contain rounded-xl border border-blue-500/10 bg-gray-800 cursor-zoom-in"
             @mousemove="zoom = true; moveZoom($event)"
             @mouseleave="zoom = false"
             @mouseenter="zoom = true"
        >
        <!-- Zoom efecto -->
        <div x-show="zoom" class="hidden md:block absolute top-0 left-full ml-4 w-80 h-80 border-2 border-blue-500 rounded-xl overflow-hidden bg-black z-20" style="box-shadow:0 0 20px #3b82f6;">
          <img :src="mainImage" alt="Zoom" class="w-full h-full object-contain"
               :style="'transform: scale(2); transform-origin: ' + zoomX + '% ' + zoomY + '%;'">
        </div>
      </div>
      <div class="flex gap-2 mt-2">
        @foreach ($producto->imagenes as $imagen)
          <img src="{{ url('storage/'.$imagen) }}" alt="Miniatura" class="w-16 h-16 object-contain rounded-lg border border-blue-500/10 cursor-pointer hover:border-blue-500 transition-all duration-200"
               :class="mainImage === '{{ url('storage/'.$imagen) }}' ? 'ring-2 ring-blue-500' : ''"
               @click="setMain('{{ url('storage/'.$imagen) }}')">
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
        @if(!$producto->en_stock || $producto->cantidad <= 0)
          <div class="p-4 mb-2 bg-red-700/80 text-white text-center rounded-lg font-bold text-lg border border-red-400 animate-pulse">
            Sin stock disponible
          </div>
        @endif
        @livewire('add-to-cart-button', ['productoId' => $producto->id, 'maxCantidad' => $producto->cantidad, 'showCantidadInput' => true], key('add-to-cart-'.$producto->id))
      </div>
    </div>
  </div>

  {{-- Reseñas de producto --}}
  <div class="max-w-3xl mx-auto mt-12 bg-gray-900/90 rounded-2xl shadow-xl p-8 border border-blue-500/20">
    <h2 class="text-2xl font-bold text-blue-400 mb-6 flex items-center gap-2">
      <svg class="w-7 h-7 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
      Reseñas de clientes
    </h2>
    @if($reviews->isEmpty())
      <p class="text-gray-300">Este producto aún no tiene reseñas.</p>
    @else
      <div class="space-y-6">
        @foreach($reviews as $review)
          <div class="bg-gray-800/80 rounded-xl p-4 border border-blue-500/10" x-data="{ edit: false, rating: {{ $review->rating }}, comentario: @js($review->comentario) }">
            <div class="flex items-center gap-2 mb-2">
              <span class="font-bold text-white">{{ $review->user->name }}</span>
              <span class="flex items-center">
                @for($i=1; $i<=5; $i++)
                  <svg class="w-5 h-5 @if($i <= $review->rating) text-yellow-400 @else text-gray-500 @endif" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                @endfor
              </span>
              @if(Auth::check() && Auth::id() === $review->user_id)
                <button class="ml-2 text-blue-400 hover:underline text-xs" @click="edit = !edit">Editar</button>
                <form method="POST" action="{{ route('producto.review.destroy', [$producto->id, $review->id]) }}" class="inline" onsubmit="return confirm('¿Eliminar reseña?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="ml-2 text-red-400 hover:underline text-xs">Eliminar</button>
                </form>
              @endif
            </div>
            <template x-if="!edit">
              <div class="text-gray-200" x-text="comentario"></div>
            </template>
            <template x-if="edit">
              <form method="POST" action="{{ route('producto.review.update', [$producto->id, $review->id]) }}" class="space-y-2 mt-2">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-1">
                  <template x-for="i in 5" :key="i">
                    <svg @click="rating = i; $refs.rating.value = i" :class="{'text-yellow-400': i <= rating, 'text-gray-500': i > rating}" class="w-6 h-6 cursor-pointer transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                  </template>
                  <input type="hidden" name="rating" x-ref="rating" :value="rating" required>
                </div>
                <textarea name="comentario" x-model="comentario" rows="2" class="w-full rounded bg-gray-800 text-white border border-blue-500/20 focus:border-blue-500 outline-none p-2" required maxlength="1000"></textarea>
                <div class="flex gap-2">
                  <button type="submit" class="py-1 px-4 bg-blue-500 rounded-lg text-sm font-bold text-white hover:bg-blue-600 transition-colors shadow">Guardar</button>
                  <button type="button" class="py-1 px-4 bg-gray-700 rounded-lg text-sm font-bold text-white hover:bg-gray-600 transition-colors shadow" @click="edit = false">Cancelar</button>
                </div>
              </form>
            </template>
          </div>
        @endforeach
      </div>
    @endif
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