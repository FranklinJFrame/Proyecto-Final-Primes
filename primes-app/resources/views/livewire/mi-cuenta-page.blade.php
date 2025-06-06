<div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 text-white">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Cabecera con efecto de neón -->
        <div class="text-center mb-16 relative">
            <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full transform -translate-y-1/2"></div>
            <h1 class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 mb-4 relative z-10 animate-pulse">
                Mi Cuenta
            </h1>
            <p class="text-xl text-gray-300 relative z-10">
                Bienvenido, <span class="font-bold text-blue-400">{{ auth()->user()->name }}</span>
            </p>
        </div>

        <!-- Grid principal con efecto de cristal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel izquierdo: Datos personales -->
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl p-8 border border-blue-500/20 shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <h2 class="text-2xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Datos Personales
                </h2>
                
                <div class="space-y-6">
                    <div class="bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                        <span class="text-gray-400 block text-sm mb-1">Nombre:</span>
                        <span class="text-white font-bold text-lg">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                        <span class="text-gray-400 block text-sm mb-1">Email:</span>
                        <span class="text-white text-lg">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                        <span class="text-gray-400 block text-sm mb-1">Teléfono:</span>
                        <span class="text-white text-lg">{{ auth()->user()->telefono ?? 'No definido' }}</span>
                    </div>

                    <!-- Información de cuenta con efecto de neón -->
                    <div class="pt-6 border-t border-blue-500/20">
                        <div class="flex justify-between text-sm mb-3 bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300">
                            <span class="text-gray-400">ID de Usuario:</span>
                            <span class="text-white font-mono">#00{{ auth()->id() }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-3 bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300">
                            <span class="text-gray-400">Estado:</span>
                            <span class="text-green-400 font-bold">Activo</span>
                        </div>
                        <div class="flex justify-between text-sm bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all duration-300">
                            <span class="text-gray-400">Miembro desde:</span>
                            <span class="text-white">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel central: Últimos pedidos -->
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl p-8 border border-blue-500/20 shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <h2 class="text-2xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Últimos Pedidos
                </h2>
                
                @php
                    $ultimosPedidos = auth()->user()->pedidos()->with('productos.producto')->latest()->take(3)->get();
                @endphp
                
                @if($ultimosPedidos->count() > 0)
                    <div class="space-y-6">
                        @foreach($ultimosPedidos as $pedido)
                            <div class="bg-gray-700/30 rounded-xl p-6 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-400 text-sm">Pedido #{{ $pedido->id }}</span>
                                    <span class="text-sm {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }} font-bold">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </div>
                                
                                <div class="flex gap-2 mb-4 overflow-x-auto py-1">
                                    @foreach($pedido->productos()->with('producto')->take(3)->get() as $item)
                                        <div class="relative flex-shrink-0 group">
                                            <img 
                                                src="{{ $item->producto && isset($item->producto->imagenes[0]) ? url('storage', $item->producto->imagenes[0]) : asset('img/default-product.jpg') }}"
                                                alt="{{ $item->producto->nombre ?? 'Producto' }}" 
                                                class="w-16 h-16 rounded-xl object-cover bg-gray-600 transform transition-all duration-300 group-hover:scale-110"
                                                loading="lazy"
                                                onerror="this.src='/img/default-product.jpg'"
                                            >
                                            @if($item->cantidad > 1)
                                                <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow-lg">
                                                    {{ $item->cantidad }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if($pedido->productos()->count() > 3)
                                        <div class="relative flex-shrink-0 w-16 h-16 rounded-xl bg-gray-600 flex items-center justify-center text-gray-300 text-sm font-bold shadow-lg">
                                            +{{ $pedido->productos()->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Total:</span>
                                    <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600">
                                        RD$ {{ number_format($pedido->total_general, 2) }}
                                    </span>
                                </div>
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-sm {{ $pedido->estado === 'entregado' ? 'text-green-400' : ($pedido->estado === 'cancelado' ? 'text-red-400' : 'text-yellow-400') }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                    <a href="{{ route('pedidos.detalle', $pedido->id) }}" class="text-sm text-blue-400 hover:text-blue-300 flex items-center gap-1 transition-all duration-300 hover:gap-2">
                                        Ver detalles
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6 text-center">
                            <a href="{{ route('pedidos') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                Ver historial completo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-700/30 rounded-xl p-8 text-center">
                        <p class="text-gray-300 mb-6 text-lg">No tienes pedidos recientes.</p>
                        <a href="/products" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                            Explorar productos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Panel derecho: Dirección y Tarjetas -->
            <div class="space-y-8">
                <!-- Dirección principal -->
                <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl p-8 border border-blue-500/20 shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Mis Direcciones
                        </h2>
                        <div>
                            @if($direcciones->count() < 3)
                                <button wire:click="nuevaDireccion" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nueva
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if(session('error'))
                        <div class="bg-red-900/50 text-red-200 p-4 rounded-xl mb-6 border border-red-500/30 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-900/50 text-green-200 p-4 rounded-xl mb-6 border border-green-500/30 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($mostrarFormulario)
                        <div class="bg-gray-700/30 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-semibold text-white mb-6">{{ $modo === 'crear' ? 'Nueva Dirección' : 'Editar Dirección' }}</h3>
                            @if($errors->any())
                                <div class="bg-red-500/20 text-red-400 p-4 rounded-xl mb-6 border border-red-500/30 text-sm text-center">
                                    Este dato no es válido para una dirección
                                </div>
                            @endif
                            <form wire:submit.prevent="{{ $modo === 'crear' ? 'crearDireccion' : 'actualizarDireccion' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-gray-300 text-sm mb-2 block">Nombre</label>
                                        <input wire:model.defer="nombre" type="text" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-2 block">Apellido</label>
                                        <input wire:model.defer="apellido" type="text" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-2 block">Teléfono</label>
                                        <input wire:model.defer="telefono" type="text" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-gray-300 text-sm mb-2 block">Dirección</label>
                                        <input wire:model.defer="direccion_calle" type="text" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-2 block">Ciudad</label>
                                        <input wire:model.defer="ciudad" type="text" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-sm mb-2 block">Estado</label>
                                        <select wire:model.defer="estado" class="w-full bg-gray-600/50 border-gray-500 rounded-xl text-white focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                            <option value="">Selecciona una provincia</option>
                                            <option value="Distrito Nacional">Distrito Nacional</option>
                                            <option value="Santo Domingo">Santo Domingo</option>
                                            <option value="Santiago">Santiago</option>
                                            <option value="La Vega">La Vega</option>
                                            <option value="San Cristóbal">San Cristóbal</option>
                                            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
                                            <option value="La Romana">La Romana</option>
                                            <option value="Puerto Plata">Puerto Plata</option>
                                            <option value="San Francisco de Macorís">San Francisco de Macorís</option>
                                            <option value="Barahona">Barahona</option>
                                            <option value="San Juan de la Maguana">San Juan de la Maguana</option>
                                            <option value="Azua">Azua</option>
                                            <option value="Bonao">Bonao</option>
                                            <option value="Moca">Moca</option>
                                            <option value="Baní">Baní</option>
                                            <option value="Higuey">Higuey</option>
                                            <option value="Nagua">Nagua</option>
                                            <option value="San José de Ocoa">San José de Ocoa</option>
                                            <option value="San Juan">San Juan</option>
                                            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
                                            <option value="San Rafael del Yuma">San Rafael del Yuma</option>
                                            <option value="Santo Domingo Este">Santo Domingo Este</option>
                                            <option value="Santo Domingo Norte">Santo Domingo Norte</option>
                                            <option value="Santo Domingo Oeste">Santo Domingo Oeste</option>
                                            <option value="Valverde">Valverde</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-4 mt-6">
                                    <button type="button" wire:click="resetForm" class="px-4 py-2 border border-gray-500 rounded-xl text-gray-300 hover:bg-gray-700 transition-all duration-300">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105">
                                        {{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    @if($direcciones->count() > 0)
                        <div class="space-y-4">
                            @foreach($direcciones as $dir)
                                <div class="bg-gray-700/30 rounded-xl p-6 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white text-lg">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                                        <div class="text-sm text-blue-400">{{ $loop->index === 0 ? 'Principal' : 'Alternativa ' . $loop->index }}</div>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Dirección:</span>
                                            <span class="text-white block mt-1">{{ $dir->direccion_calle }}</span>
                                        </div>
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Ciudad:</span>
                                            <span class="text-white block mt-1">{{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</span>
                                        </div>
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Teléfono:</span>
                                            <span class="text-white block mt-1">{{ $dir->telefono }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button wire:click="edit({{ $dir->id }})" class="px-4 py-2 bg-yellow-600/80 hover:bg-yellow-600 text-white rounded-xl text-sm transition-all duration-300 transform hover:scale-105 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $dir->id }})" class="px-4 py-2 bg-red-600/80 hover:bg-red-600 text-white rounded-xl text-sm transition-all duration-300 transform hover:scale-105 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-700/30 rounded-xl p-8 text-center">
                            <p class="text-gray-300 mb-6 text-lg">No tienes direcciones registradas.</p>
                            <button wire:click="nuevaDireccion" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                Añadir dirección
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Tarjetas de crédito -->
                <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl p-8 border border-blue-500/20 shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Mis Tarjetas
                        </h2>
                        <div>
                            @php
                                $tarjetas = auth()->user()->tarjetas()->get();
                            @endphp
                            @if($tarjetas->count() < 3)
                                <a href="{{ route('tarjetas') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="bg-gray-700/30 rounded-xl p-6 hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-105">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white text-lg">{{ $tarjeta->nombre_tarjeta }}</div>
                                        @if($tarjeta->es_predeterminada)
                                            <div class="text-sm text-blue-400">Predeterminada</div>
                                        @endif
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Número:</span>
                                            <span class="text-white block mt-1">**** **** **** {{ substr($tarjeta->numero_tarjeta, -4) }}</span>
                                        </div>
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Vence:</span>
                                            <span class="text-white block mt-1">{{ $tarjeta->vencimiento }}</span>
                                        </div>
                                        <div class="bg-gray-600/30 rounded-lg p-3">
                                            <span class="text-gray-400 text-sm">Tipo:</span>
                                            <span class="text-white block mt-1">{{ ucfirst($tarjeta->tipo_tarjeta) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($tarjetas->count() > 2)
                                <div class="text-center mt-4">
                                    <a href="{{ route('tarjetas') }}" class="text-blue-400 hover:text-blue-300 text-sm transition-all duration-300">
                                        Ver todas las tarjetas
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-700/30 rounded-xl p-8 text-center">
                            <p class="text-gray-300 mb-6 text-lg">No tienes tarjetas registradas.</p>
                            <a href="{{ route('tarjetas') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                Añadir tarjeta
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Botón de Cerrar Sesión -->
                <div class="mt-8 text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:scale-105">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
