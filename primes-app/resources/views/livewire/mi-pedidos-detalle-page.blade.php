<div class="bg-gray-900 min-h-screen">
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado del pedido -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Pedido #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</h1>
        <p class="text-blue-400">{{ $pedido->created_at->format('d/m/Y') }}</p>
      </div>
    </div>
    
    @php
        use Carbon\Carbon;
        $fechaMin = Carbon::parse($pedido->created_at)->addWeekdays(2)->format('d/m/Y');
        $fechaMax = Carbon::parse($pedido->created_at)->addWeekdays(5)->format('d/m/Y');
    @endphp
    <!-- Banner de tiempo estimado de entrega o estado de reembolso -->
    <div class="w-full mb-8">
        @if($pedido->estado == App\Models\Pedidos::ESTADO_REEMBOLSADO)
            <div class="rounded-lg bg-blue-100 border border-blue-300 px-6 py-4 flex items-center justify-center shadow text-blue-900 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-lg md:text-xl font-semibold">
                    Este pedido ha sido reembolsado.
                </span>
            </div>
        @elseif($pedido->estado == App\Models\Pedidos::ESTADO_PROCESO_DEVOLUCION)
            <div class="rounded-lg bg-orange-100 border border-orange-300 px-6 py-4 flex items-center justify-center shadow text-orange-900 text-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-lg md:text-xl font-semibold">
                    Este pedido está en proceso de devolución.
                </span>
            </div>
        @elseif(!in_array($pedido->estado, ['entregado', 'cancelado']))
            <div class="rounded-lg bg-yellow-100 border border-yellow-300 px-6 py-4 flex items-center justify-center shadow text-yellow-900 text-center">
                <svg class="w-7 h-7 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                </svg>
                <span class="text-lg md:text-xl font-semibold">
                    Tu pedido llegará entre el <span class="font-bold">{{ $fechaMin }}</span> y el <span class="font-bold">{{ $fechaMax }}</span>
                </span>
            </div>
        @endif
    </div>
    
    <!-- Tracking visual tipo Amazon -->
    @php
        $estado = strtolower($pedido->estado ?? 'nuevo');
        $posiciones = [
            'nuevo' => '0%',
            'procesando' => '20%',
            'enviado' => '60%',
            'entregado' => '85%',
        ];
        $posicionCamion = $posiciones[$estado] ?? '0%';
    @endphp
    <div class="relative w-full max-w-xl mx-auto h-32 my-8 bg-white rounded-xl shadow-lg border border-gray-200">
        <!-- Línea de ruta -->
        <div class="absolute top-1/2 left-0 w-full h-2 bg-gray-300 rounded-full" style="transform: translateY(-50%);"></div>
        <!-- Casa (meta) -->
        <div class="absolute top-1/2 right-0 -translate-y-1/2">
            <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="Casa" width="48" height="48" class="drop-shadow-lg" loading="lazy">
        </div>
        <!-- Camión -->
        <div class="absolute top-1/2" style="left: {{ $posicionCamion }}; transform: translate(-50%, -50%); transition: left 1s cubic-bezier(.4,2,.6,1);">
            <img src="https://cdn-icons-png.flaticon.com/512/44/44266.png" alt="Camión" width="64" height="40" class="drop-shadow-lg" loading="lazy">
        </div>
    </div>
    <!-- Fin tracking visual -->

    @if(now()->gt(Carbon::parse($pedido->created_at)->addWeekdays(5)) && $pedido->estado != 'entregado' && $pedido->notas)
    <div class="bg-red-100 border border-red-300 px-6 py-4 rounded-lg text-red-900 mt-4">
      <strong>Motivo del retraso:</strong> {{ $pedido->notas }}
    </div>
    @endif
    
    <!-- Tarjetas de información -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <!-- Tarjeta Cliente -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-blue-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Cliente</h3>
            <p class="text-white text-lg font-bold">{{ $pedido->user->name }}</p>
          </div>
        </div>
      </div>
      
      <!-- Tarjeta Fecha -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-purple-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Fecha</h3>
            <p class="text-white text-lg font-bold">{{ $pedido->created_at->format('d-m-Y') }}</p>
          </div>
        </div>
      </div>
      
      <!-- Tarjeta Estado -->
      <div class="bg-gray-800/70 border border-gray-700/50 rounded-xl p-5 shadow-lg transform transition-all hover:scale-[1.02] hover:bg-gray-800/90">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-{{ $pedido->estado_pago == 'Pagado' ? 'green' : 'red' }}-900/60 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-{{ $pedido->estado_pago == 'Pagado' ? 'green' : 'red' }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-1">Estado</h3>
            <div class="flex flex-col space-y-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pedido->estado == 'Nuevo' ? 'bg-yellow-900/60 text-yellow-400 border border-yellow-500/50' : 'bg-green-900/60 text-green-400 border border-green-500/50' }}">
                {{ $pedido->estado }}
              </span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pedido->estado_pago == 'Pagado' ? 'bg-green-900/60 text-green-400 border border-green-500/50' : 'bg-red-900/60 text-red-400 border border-red-500/50' }}">
                {{ $pedido->estado_pago }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
      <div class="md:w-3/4">
        <!-- Tabla de productos -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="p-4 bg-gray-800 border-b border-gray-700 flex items-center">
            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-white">Productos</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-800">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Producto</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Precio</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cantidad</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                </tr>
              </thead>
              <tbody class="bg-gray-900 divide-y divide-gray-800">
                @foreach($pedido->productos as $item)
                <tr class="hover:bg-gray-800/50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      @if($item->producto && isset($item->producto->imagenes) && count($item->producto->imagenes) > 0)
                      <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-md object-cover border border-gray-700" src="{{ url('storage', $item->producto->imagenes[0]) }}" alt="{{ $item->producto->nombre }}">
                      </div>
                      @else
                      <div class="flex-shrink-0 h-10 w-10 bg-gray-800 rounded-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                      @endif
                      <div class="ml-4">
                        <div class="text-sm font-medium text-white">{{ $item->producto ? $item->producto->nombre : 'Producto no disponible' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-300">RD$ {{ number_format($item->precio_unitario, 2) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-300">{{ $item->cantidad }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-blue-400">RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Dirección de envío -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6">
          <div class="flex items-center mb-4">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-white">Dirección de Envío</h2>
          </div>
          
          @if($pedido->user && $pedido->user->direccions && $pedido->user->direccions->count() > 0)
            @php $direccion = $pedido->user->direccions->first(); @endphp
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->user->name }}</p>
              <p class="text-gray-300">{{ $direccion->direccion_calle }}</p>
              <p class="text-gray-300">{{ $direccion->ciudad }}, {{ $direccion->estado }}</p>
              <p class="text-gray-300">{{ $direccion->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $direccion->telefono }}</p>
            </div>
          @elseif($pedido->direccion)
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->user->name }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->direccion_calle }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}</p>
              <p class="text-gray-300">{{ $pedido->direccion->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $pedido->direccion->telefono }}</p>
            </div>
          @elseif(isset($pedido->direccion_calle))
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700">
              <p class="font-medium text-white mb-2">{{ $pedido->nombre ?? $pedido->user->name }} {{ $pedido->apellido ?? '' }}</p>
              <p class="text-gray-300">{{ $pedido->direccion_calle }}</p>
              <p class="text-gray-300">{{ $pedido->ciudad }}, {{ $pedido->estado_direccion ?? $pedido->estado }}</p>
              <p class="text-gray-300">{{ $pedido->codigo_postal }}</p>
              <p class="text-gray-300">Tel: {{ $pedido->telefono }}</p>
            </div>
          @else
            <div class="bg-gray-900/60 rounded-lg p-4 border border-gray-700 text-center">
              <p class="text-gray-400">No hay información de dirección disponible</p>
            </div>
          @endif
        </div>

        <!-- Información de envío extra -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 mt-6">
          <div class="flex items-center mb-4">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4" />
            </svg>
            <h2 class="text-lg font-semibold text-white">Envío</h2>
          </div>
          <div class="text-gray-300 mb-2"><span class="font-semibold text-white">Transportista:</span> {{ $pedido->metodo_envio == 'tecnobox_transport' ? 'Tecnobox Transport' : ($pedido->metodo_envio == 'caribes_tour' ? 'Caribes Tour' : $pedido->metodo_envio) }}</div>
        </div>

        <!-- Notas adicionales del pedido -->
        @if($pedido->notas)
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 mt-6">
          <div class="flex items-center mb-4">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4-4 4 4" />
            </svg>
            <h2 class="text-lg font-semibold text-white">Notas adicionales del pedido</h2>
          </div>
          <div class="text-gray-300">{{ $pedido->notas }}</div>
        </div>
        @endif

        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Estado del Pedido:
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @switch($pedido->estado)
                        @case('nuevo') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100 @break
                        @case('procesando') bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100 @break
                        @case('enviado') bg-green-100 text-green-800 dark:bg-green-600 dark:text-green-100 @break
                        @case('entregado') bg-emerald-100 text-emerald-800 dark:bg-emerald-600 dark:text-emerald-100 @break
                        @case('cancelado') bg-red-100 text-red-800 dark:bg-red-600 dark:text-red-100 @break
                        @case(App\Models\Pedidos::ESTADO_PROCESO_DEVOLUCION) bg-orange-100 text-orange-800 dark:bg-orange-600 dark:text-orange-100 @break
                        @case(App\Models\Pedidos::ESTADO_REEMBOLSADO) bg-purple-100 text-purple-800 dark:bg-purple-600 dark:text-purple-100 @break
                        @default bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @break
                    @endswitch
                ">
                    {{ ucfirst($pedido->estado) }}
                </span>
            </h3>

            {{-- Mostrar información de devoluciones --}}
            @if($devoluciones && $devoluciones->count() > 0)
                <div class="mt-4 space-y-3">
                    @foreach($devoluciones as $devolucion)
                        <div class="p-4 border rounded-md bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                Solicitud de Devolución ({{ $devolucion->created_at->format('d/m/Y H:i') }}) - Estado:
                                <span class="font-semibold
                                    @switch($devolucion->estado)
                                        @case('pendiente') text-yellow-600 dark:text-yellow-400 @break
                                        @case('aprobada') text-green-600 dark:text-green-400 @break
                                        @case('rechazada') text-red-600 dark:text-red-400 @break
                                    @endswitch
                                ">
                                    {{ ucfirst($devolucion->estado) }}
                                </span>
                            </p>
                            @if($devolucion->motivo)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                <span class="font-semibold">Motivo:</span> {{ $devolucion->motivo }}
                            </p>
                            @endif
                            @if($devolucion->admin_notes)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                <span class="font-semibold">Notas del Administrador:</span> {{ $devolucion->admin_notes }}
                            </p>
                            @endif
                            @if(!empty($devolucion->imagenes_adjuntas))
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @foreach($devolucion->imagenes_adjuntas as $img)
                                        @php
                                            // Siempre mostrar desde public/devoluciones, sin importar el path guardado
                                            $imgName = basename($img);
                                            $imgUrl = url('storage/public/devoluciones/' . $imgName);
                                        @endphp
                                        <a href="{{ $imgUrl }}" target="_blank" class="block border border-blue-500 dark:border-blue-400 rounded-lg overflow-hidden shadow-lg hover:scale-105 transition-transform focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <img src="{{ $imgUrl }}" class="w-16 h-16 object-cover rounded-lg bg-gray-800 border border-gray-700" alt="Imagen adjunta">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
      </div>

      <div class="md:w-1/4">
        <!-- Resumen del pedido -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6">
          <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Resumen
          </h2>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-400">Subtotal:</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }), 2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">ITBIS (18%):</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; }) * 0.18, 2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Envío:</span>
              <span class="font-medium text-white">RD$ {{ number_format($pedido->costo_envio ?? 0, 2) }}</span>
            </div>
            <hr class="border-gray-700">
            <div class="flex justify-between text-lg">
              <span class="font-bold text-white">Total:</span>
              <span class="font-bold text-blue-400">RD$ {{ number_format($pedido->total_general, 2) }}</span>
            </div>
          </div>

          {{-- Datos de la Tarjeta --}}
          <div class="mt-6 pt-6 border-t border-gray-700">
            <h3 class="text-md font-semibold text-white mb-3">Datos de la Tarjeta</h3>
            <div class="bg-gray-700 rounded-lg p-4 text-white space-y-2">
              <div><span class="font-semibold">Cliente:</span> Franklin</div>
              <div><span class="font-semibold">Tipo Tarjeta:</span> paypal</div>
              <div><span class="font-semibold">Terminación:</span> **** **** **** 4435</div>
              <div><span class="font-semibold">Nombre en Tarjeta:</span> Angel Espinal</div>
            </div>
          </div>

          {{-- Botón para Solicitar Devolución --}}
          @if($pedido->estado === 'entregado')
            <a href="{{ route('devoluciones.create', $pedido->id) }}"
               class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 mb-3 mt-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v6.5h6.5a.75.75 0 010 1.5h-6.5v6.5a.75.75 0 01-1.5 0v-6.5h-6.5a.75.75 0 010-1.5h6.5v-6.5A.75.75 0 0110 2zM8.293 13.207a1 1 0 010-1.414L10.586 10 8.293 7.707a1 1 0 111.414-1.414l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                <path d="M4.5 2.5a.5.5 0 00-.5.5v14a.5.5 0 00.5.5h11a.5.5 0 00.5-.5v-14a.5.5 0 00-.5-.5h-11zM3 3a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V3z" />
                <path d="M7 7h6v2H7V7zm0 4h6v2H7v-2z"/>
              </svg>
              Solicitar Devolución
            </a>
          @endif
        </div>
      </div>
    </div>

    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      {{-- ... existing code ... --}}
    </div>
  </div>
</div>
