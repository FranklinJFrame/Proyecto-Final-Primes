<div class="min-h-screen bg-gray-900 text-white">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Cabecera -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-2">
                Mi Cuenta
            </h1>
            <p class="text-lg text-gray-300">
                Bienvenido, <span class="font-bold text-blue-400">{{ auth()->user()->name }}</span>
            </p>
        </div>

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel izquierdo: Datos personales -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h2 class="text-xl font-bold mb-6 text-blue-400">Datos Personales</h2>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-400 block text-sm">Nombre:</span>
                        <span class="text-white font-bold">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-sm">Email:</span>
                        <span class="text-white">{{ auth()->user()->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-sm">Teléfono:</span>
                        <span class="text-white">{{ auth()->user()->telefono ?? 'No definido' }}</span>
                    </div>

                    <!-- Información de cuenta -->
                    <div class="pt-4 border-t border-gray-700">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-400">ID de Usuario:</span>
                            <span class="text-white font-mono">#00{{ auth()->id() }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-400">Estado:</span>
                            <span class="text-green-400">Activo</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Miembro desde:</span>
                            <span class="text-white">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel central: Últimos pedidos -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h2 class="text-xl font-bold mb-6 text-blue-400">Últimos Pedidos</h2>
                
                @php
                    $ultimosPedidos = auth()->user()->pedidos()->latest()->take(3)->get();
                @endphp
                
                @if($ultimosPedidos->count() > 0)
                    <div class="space-y-4">
                        @foreach($ultimosPedidos as $pedido)
                            <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition-all duration-300">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-bold text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        Pedido #{{ $pedido->id }}
                                    </span>
                                    <span class="text-sm text-gray-300">
                                        {{ $pedido->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <!-- Productos del pedido -->
                                <div class="flex gap-2 mb-3 overflow-x-auto py-1">
                                    @foreach($pedido->productos()->with('producto')->take(3)->get() as $item)
                                        <div class="relative flex-shrink-0">
                                            <img 
                                                src="{{ $item->producto && isset($item->producto->imagenes[0]) ? url('storage', $item->producto->imagenes[0]) : asset('img/default-product.jpg') }}"
                                                alt="{{ $item->producto->nombre ?? 'Producto' }}" 
                                                class="w-12 h-12 rounded-md object-cover bg-gray-600"
                                                loading="lazy"
                                                onerror="this.src='/img/default-product.jpg'"
                                            >
                                            @if($item->cantidad > 1)
                                                <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                                    {{ $item->cantidad }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if($pedido->productos()->count() > 3)
                                        <div class="relative flex-shrink-0 w-12 h-12 rounded-md bg-gray-600 flex items-center justify-center text-gray-300 text-xs font-bold">
                                            +{{ $pedido->productos()->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Total:</span>
                                    <span class="text-lg font-bold text-white">
                                        RD$ {{ number_format($pedido->total_general, 2) }}
                                    </span>
                                </div>
                                
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="text-sm {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                    <a href="{{ route('pedidos.detalle', $pedido->id) }}" class="text-sm text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                        Ver detalles
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4 text-center">
                            <a href="{{ route('pedidos') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ver historial completo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-700/50 rounded-lg p-6 text-center">
                        <p class="text-gray-300 mb-4">No tienes pedidos recientes.</p>
                        <a href="/products" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Explorar productos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Panel derecho: Dirección y Tarjetas -->
            <div class="space-y-6">
                <!-- Dirección principal -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-blue-400">Mis Direcciones</h2>
                        <div>
                            @if($direcciones->count() < 3)
                                <button wire:click="nuevaDireccion" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nueva
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if(session('error'))
                        <div class="bg-red-900/50 text-red-200 p-3 rounded-lg mb-4 border border-red-500/30 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-900/50 text-green-200 p-3 rounded-lg mb-4 border border-green-500/30 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($mostrarFormulario)
                        <div class="bg-gray-700/50 rounded-lg p-5 mb-4">
                            <h3 class="text-lg font-semibold text-white mb-4">{{ $modo === 'crear' ? 'Nueva Dirección' : 'Editar Dirección' }}</h3>
                            @if($errors->any())
                                <div class="bg-red-500/20 text-red-400 p-3 rounded-lg mb-4 border border-red-500/30 text-sm text-center">
                                    Este dato no es válido para una dirección
                                </div>
                            @endif
                            <form wire:submit.prevent="{{ $modo === 'crear' ? 'save' : 'update' }}" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Nombre</label>
                                        <input wire:model.defer="nombre" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Apellido</label>
                                        <input wire:model.defer="apellido" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Teléfono</label>
                                        <input wire:model.defer="telefono" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-gray-300 text-sm mb-1 block">Dirección</label>
                                        <input wire:model.defer="direccion_calle" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Ciudad</label>
                                        <input wire:model.defer="ciudad" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Estado</label>
                                        <select wire:model.defer="estado" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="">Selecciona una provincia</option>
                                            <option value="Distrito Nacional">Distrito Nacional</option>
                                            <option value="Santo Domingo">Santo Domingo</option>
                                            <option value="Santiago">Santiago</option>
                                            <option value="La Vega">La Vega</option>
                                            <option value="San Cristóbal">San Cristóbal</option>
                                            <option value="Puerto Plata">Puerto Plata</option>
                                            <option value="Duarte">Duarte</option>
                                            <option value="La Romana">La Romana</option>
                                            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
                                            <option value="La Altagracia">La Altagracia</option>
                                            <option value="Peravia">Peravia</option>
                                            <option value="Azua">Azua</option>
                                            <option value="Barahona">Barahona</option>
                                            <option value="San Juan">San Juan</option>
                                            <option value="Monseñor Nouel">Monseñor Nouel</option>
                                            <option value="Monte Plata">Monte Plata</option>
                                            <option value="Valverde">Valverde</option>
                                            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
                                            <option value="Espaillat">Espaillat</option>
                                            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
                                            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
                                            <option value="Samaná">Samaná</option>
                                            <option value="Bahoruco">Bahoruco</option>
                                            <option value="El Seibo">El Seibo</option>
                                            <option value="Hato Mayor">Hato Mayor</option>
                                            <option value="Independencia">Independencia</option>
                                            <option value="Pedernales">Pedernales</option>
                                            <option value="Elías Piña">Elías Piña</option>
                                            <option value="Monte Cristi">Monte Cristi</option>
                                            <option value="Dajabón">Dajabón</option>
                                            <option value="San José de Ocoa">San José de Ocoa</option>
                                            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-1 block">Código Postal</label>
                                        <input wire:model.defer="codigo_postal" type="text" class="w-full bg-gray-600 border-gray-500 rounded-md text-white focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" wire:click="resetForm" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        {{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    @if($direcciones->count() > 0)
                        <div class="space-y-4">
                            @foreach($direcciones as $dir)
                                <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition-all duration-300">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                                        <div class="text-xs text-blue-400">{{ $loop->index === 0 ? 'Principal' : 'Alternativa ' . $loop->index }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="mb-1">
                                            <span class="text-gray-300 text-sm">Dirección:</span>
                                            <span class="text-white">{{ $dir->direccion_calle }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-gray-300 text-sm">Ciudad:</span>
                                            <span class="text-white">{{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-300 text-sm">Teléfono:</span>
                                            <span class="text-white">{{ $dir->telefono }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-end gap-2">
                                        <button wire:click="edit({{ $dir->id }})" class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md text-sm transition-all duration-300 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $dir->id }})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm transition-all duration-300 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-700/50 rounded-lg p-6 text-center">
                            <p class="text-gray-300 mb-4">No tienes direcciones registradas.</p>
                            <button wire:click="nuevaDireccion" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Añadir dirección
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Tarjetas de crédito -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-blue-400">Mis Tarjetas</h2>
                        <div>
                            @php
                                $tarjetas = auth()->user()->tarjetas()->get();
                            @endphp
                            @if($tarjetas->count() < 3)
                                <a href="{{ route('tarjetas') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nueva
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($tarjetas->count() > 0)
                        <div class="space-y-4">
                            @foreach($tarjetas->take(2) as $tarjeta)
                                <div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition-all duration-300">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white">{{ $tarjeta->nombre_tarjeta }}</div>
                                        @if($tarjeta->es_predeterminada)
                                            <div class="text-xs text-blue-400">Predeterminada</div>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <div class="mb-1">
                                            <span class="text-gray-300 text-sm">Número:</span>
                                            <span class="text-white">**** **** **** {{ substr($tarjeta->numero_tarjeta, -4) }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-gray-300 text-sm">Vence:</span>
                                            <span class="text-white">{{ $tarjeta->vencimiento }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-300 text-sm">Tipo:</span>
                                            <span class="text-white">{{ ucfirst($tarjeta->tipo_tarjeta) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($tarjetas->count() > 2)
                                <div class="text-center mt-2">
                                    <a href="{{ route('tarjetas') }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                        Ver todas las tarjetas
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-700/50 rounded-lg p-6 text-center">
                            <p class="text-gray-300 mb-4">No tienes tarjetas registradas.</p>
                            <a href="{{ route('tarjetas') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Añadir tarjeta
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Botón de Cerrar Sesión -->
                <div class="mt-4 text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
