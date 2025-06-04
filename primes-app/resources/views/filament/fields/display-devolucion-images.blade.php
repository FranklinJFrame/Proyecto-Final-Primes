{{-- resources/views/filament/fields/display-devolucion-images.blade.php --}}
@php
    $record = $getRecord();
    $imagenes = $record->imagenes_adjuntas ?? [];
@endphp

@if(!empty($imagenes) && is_array($imagenes))
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-2">
        @foreach($imagenes as $index => $imgPath)
            @if(is_string($imgPath) && !empty($imgPath))
                <a href="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imgPath) }}" target="_blank" class="block border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($imgPath) }}" 
                         alt="Imagen adjunta {{ $index + 1 }}" 
                         class="w-full h-32 object-cover bg-gray-100 dark:bg-gray-700">
                </a>
            @else
                <!-- Opcional: mostrar un placeholder o mensaje si la ruta de la imagen no es válida -->
                <div class="w-full h-32 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                    <span class="text-xs text-gray-500">Ruta inválida</span>
                </div>
            @endif
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No hay imágenes adjuntas o el formato no es correcto.</p>
@endif 