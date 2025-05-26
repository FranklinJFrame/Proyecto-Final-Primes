@extends('layouts.minimal') {{-- Usando el nuevo layout minimalista --}}

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-800">Solicitar Devolución para el Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</h1>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-5 shadow" role="alert">
                    <strong class="font-bold block">¡Ups! Algo salió mal.</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('devoluciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Selecciona los Productos a Devolver</h2>
                    @if($pedido->productos->isEmpty())
                        <p class="text-gray-600">No hay productos en este pedido.</p>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Comprado</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad a Devolver</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pedido->productos as $index => $item)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($item->producto && isset($item->producto->imagenes) && count($item->producto->imagenes) > 0)
                                                    <img src="{{ url('storage/' . $item->producto->imagenes[0]) }}" alt="{{ $item->producto->nombre }}" class="h-12 w-12 object-cover rounded-md border border-gray-300">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->producto->nombre ?? 'Producto no disponible' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-600">{{ $item->cantidad }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex items-center justify-center">
                                                    <input type="number" 
                                                           name="productos_a_devolver[{{ $index }}][cantidad]" 
                                                           min="0" 
                                                           max="{{ $item->cantidad }}" 
                                                           value="{{ old('productos_a_devolver.'.$index.'.cantidad', 0) }}" 
                                                           class="w-20 text-center shadow-sm appearance-none border border-gray-300 rounded-md py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cantidad-devolver"
                                                           data-item-id="{{ $item->id }}"
                                                           title="Ingresa la cantidad que deseas devolver (0-{{ $item->cantidad }})">
                                                    <input type="hidden" name="productos_a_devolver[{{ $index }}][pedido_producto_id]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la Devolución <span class="text-red-500">*</span></label>
                    <textarea name="motivo" id="motivo" rows="4" class="shadow-sm appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 bg-white" required>{{ old('motivo') }}</textarea>
                </div>

                <div class="mb-8">
                    <label for="imagenes_devolucion" class="block text-sm font-medium text-gray-700 mb-1">Adjuntar Imágenes (opcional)</label>
                    <input type="file" name="imagenes_devolucion[]" id="imagenes_devolucion" multiple 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">Puedes seleccionar varias imágenes. Formatos: JPG, PNG, GIF. Máx 2MB por imagen.</p>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-5 border-t border-gray-200">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar y Volver
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2.99999 2.5C2.99999 2.22386 3.22385 2 3.49999 2H12.4142C12.6794 2 12.9337 2.10536 13.1213 2.29289L16.7071 5.87868C16.8946 6.06621 17 6.32057 17 6.58579V17.5C17 17.7761 16.7761 18 16.5 18H3.49999C3.22385 18 2.99999 17.7761 2.99999 17.5V2.5ZM4.49999 3.5V16.5H15.5V7H12.5C12.2239 7 12 6.77614 12 6.5V3.5H4.49999Z" clip-rule="evenodd" />
                        </svg>
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cantidadesInputs = document.querySelectorAll('.cantidad-devolver');

    cantidadesInputs.forEach(input => {
        const maxCantidad = parseInt(input.max);
        
        input.addEventListener('input', function() {
            let valor = parseInt(this.value) || 0;
            
            // Asegurar que el valor esté entre 0 y la cantidad máxima
            if (valor < 0) {
                valor = 0;
            } else if (valor > maxCantidad) {
                valor = maxCantidad;
            }
            
            this.value = valor;
        });

        // Asegurar que siempre haya un valor válido
        if (input.value === '' || isNaN(input.value)) {
            input.value = '0';
        }
        
        // Prevenir valores no numéricos
        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });
});
</script>

@endsection 