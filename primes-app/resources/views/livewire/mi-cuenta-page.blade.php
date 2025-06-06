<div class="min-h-screen bg-[#111] text-white">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Cabecera -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-white mb-2 tracking-tight">Mi Cuenta</h1>
            <p class="text-lg text-gray-400">Bienvenido, <span class="font-semibold text-white">{{ auth()->user()->name }}</span></p>
        </div>
        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Datos Personales -->
            <div class="bg-[#18181b] rounded-2xl p-8 border border-[#23232a] shadow-lg flex flex-col">
                <h2 class="text-xl font-bold mb-6 text-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Datos Personales
                </h2>
                <div class="space-y-5">
                    <div class="bg-[#23232a] rounded-xl p-4">
                        <span class="text-gray-500 block text-xs mb-1">Nombre:</span>
                        <span class="text-white font-semibold text-base">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="bg-[#23232a] rounded-xl p-4">
                        <span class="text-gray-500 block text-xs mb-1">Email:</span>
                        <span class="text-gray-200 text-base">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="bg-[#23232a] rounded-xl p-4">
                        <span class="text-gray-500 block text-xs mb-1">Teléfono:</span>
                        <span class="text-gray-200 text-base">{{ auth()->user()->telefono ?? 'No definido' }}</span>
                    </div>
                    <div class="pt-4 border-t border-[#23232a]">
                        <div class="flex justify-between text-xs mb-2 bg-[#23232a] rounded-xl p-3">
                            <span class="text-gray-500">ID de Usuario:</span>
                            <span class="text-gray-300 font-mono">#00{{ auth()->id() }}</span>
                        </div>
                        <div class="flex justify-between text-xs mb-2 bg-[#23232a] rounded-xl p-3">
                            <span class="text-gray-500">Estado:</span>
                            <span class="text-green-500 font-semibold">Activo</span>
                        </div>
                        <div class="flex justify-between text-xs bg-[#23232a] rounded-xl p-3">
                            <span class="text-gray-500">Miembro desde:</span>
                            <span class="text-gray-300">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Últimos Pedidos -->
            <div class="bg-[#18181b] rounded-2xl p-8 border border-[#23232a] shadow-lg flex flex-col">
                <h2 class="text-xl font-bold mb-6 text-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Últimos Pedidos
                </h2>
                <div class="space-y-5">
                    @php
                        $ultimosPedidos = auth()->user()->pedidos()->with('productos.producto')->latest()->take(3)->get();
                    @endphp
                    @if($ultimosPedidos->count() > 0)
                        @foreach($ultimosPedidos as $pedido)
                            <div class="bg-[#23232a] rounded-xl p-5">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-400 text-xs">Pedido #{{ $pedido->id }}</span>
                                    <span class="text-xs {{ $pedido->estado === 'entregado' ? 'text-green-500' : ($pedido->estado === 'cancelado' ? 'text-red-500' : 'text-yellow-400') }} font-semibold">{{ ucfirst($pedido->estado) }}</span>
                                </div>
                                <div class="flex gap-2 mb-2 overflow-x-auto py-1">
                                    @foreach($pedido->productos()->with('producto')->take(3)->get() as $item)
                                        <div class="relative flex-shrink-0 group">
                                            <img src="{{ $item->producto && isset($item->producto->imagenes[0]) ? url('storage', $item->producto->imagenes[0]) : asset('img/default-product.jpg') }}" alt="{{ $item->producto->nombre ?? 'Producto' }}" class="w-12 h-12 rounded-lg object-cover bg-[#18181b] group-hover:scale-105 transition-all duration-300" loading="lazy" onerror="this.src='/img/default-product.jpg'">
                                            @if($item->cantidad > 1)
                                                <span class="absolute -top-1 -right-1 bg-gray-700 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center shadow">{{ $item->cantidad }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($pedido->productos()->count() > 3)
                                        <div class="relative flex-shrink-0 w-12 h-12 rounded-lg bg-[#18181b] flex items-center justify-center text-gray-400 text-xs font-bold shadow">+{{ $pedido->productos()->count() - 3 }}</div>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 text-xs">Total:</span>
                                    <span class="text-base font-bold text-white">RD$ {{ number_format($pedido->total_general, 2) }}</span>
                                </div>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-xs {{ $pedido->estado === 'entregado' ? 'text-green-500' : ($pedido->estado === 'cancelado' ? 'text-red-500' : 'text-yellow-400') }}">{{ ucfirst($pedido->estado) }}</span>
                                    <a href="{{ route('pedidos.detalle', $pedido->id) }}" class="text-xs text-gray-400 hover:text-white flex items-center gap-1 transition-all duration-200">Ver detalles<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></a>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4 text-center">
                            <a href="{{ route('pedidos') }}" class="inline-flex items-center justify-center px-5 py-2 rounded-xl border border-gray-600 text-xs font-medium text-gray-200 hover:bg-gray-800 transition-all duration-200">Ver historial completo</a>
                        </div>
                    @else
                        <div class="bg-[#23232a] rounded-xl p-8 text-center">
                            <p class="text-gray-400 mb-4 text-base">No tienes pedidos recientes.</p>
                            <a href="/products" class="inline-flex items-center justify-center px-5 py-2 rounded-xl border border-gray-600 text-xs font-medium text-gray-200 hover:bg-gray-800 transition-all duration-200">Explorar productos</a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Mis Direcciones y Tarjetas -->
            <div class="flex flex-col gap-8">
                <!-- Mis Direcciones -->
                <div class="bg-[#18181b] rounded-2xl p-8 border border-[#23232a] shadow-lg flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-200 flex items-center gap-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Mis Direcciones
                        </h2>
                        <div>
                            @if($direcciones->count() < 3)
                                <button wire:click="nuevaDireccion" class="inline-flex items-center px-6 py-3 rounded-xl bg-blue-600 text-white text-base font-semibold hover:bg-blue-700 transition-all duration-200 shadow-lg"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Nueva</button>
                            @endif
                        </div>
                    </div>

                    @if($mostrarFormulario)
                        <div class="bg-[#23232a] rounded-xl p-8 mb-8">
                            <h3 class="text-xl font-bold mb-6 text-white">{{ $modo === 'crear' ? 'Nueva Dirección' : 'Editar Dirección' }}</h3>
                            @if($errors->any())
                                <div class="bg-red-600/20 text-red-400 p-4 rounded-xl mb-6 border border-red-500/30 text-base text-center">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <form wire:submit.prevent="{{ $modo === 'crear' ? 'save' : 'update' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Nombre</label>
                                        <input wire:model.defer="nombre" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Apellido</label>
                                        <input wire:model.defer="apellido" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Teléfono</label>
                                        <input wire:model.defer="telefono" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-gray-300 text-base mb-2 block">Dirección</label>
                                        <input wire:model.defer="direccion_calle" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Ciudad</label>
                                        <input wire:model.defer="ciudad" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Estado</label>
                                        <input wire:model.defer="estado" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-300 text-base mb-2 block">Código Postal</label>
                                        <input wire:model.defer="codigo_postal" type="text" class="w-full bg-[#18181b] border border-gray-600 rounded-xl text-white text-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500 transition-all duration-300" required>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-4 mt-8">
                                    <button type="button" wire:click="resetForm" class="px-6 py-3 border border-gray-600 rounded-xl text-gray-300 text-base hover:bg-gray-800 transition-all duration-200">Cancelar</button>
                                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl text-base font-semibold shadow-lg transition-all duration-200">{{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @if($direcciones->count() > 0)
                            @foreach($direcciones as $dir)
                                <div class="bg-[#23232a] rounded-xl p-6">
                                    <div class="flex justify-between">
                                        <div class="font-bold text-white text-lg">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                                        <div class="text-base text-gray-400">{{ $loop->index === 0 ? 'Principal' : 'Alternativa ' . $loop->index }}</div>
                                    </div>
                                    <div class="mt-3 space-y-2">
                                        <div class="bg-[#18181b] rounded-lg p-3">
                                            <span class="text-gray-500 text-base">Dirección:</span>
                                            <span class="text-gray-200 block mt-1 text-lg">{{ $dir->direccion_calle }}</span>
                                        </div>
                                        <div class="bg-[#18181b] rounded-lg p-3">
                                            <span class="text-gray-500 text-base">Ciudad:</span>
                                            <span class="text-gray-200 block mt-1 text-lg">{{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</span>
                                        </div>
                                        <div class="bg-[#18181b] rounded-lg p-3">
                                            <span class="text-gray-500 text-base">Teléfono:</span>
                                            <span class="text-gray-200 block mt-1 text-lg">{{ $dir->telefono }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button wire:click="edit({{ $dir->id }})" class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl text-base font-semibold shadow transition-all duration-200 flex items-center gap-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>Editar</button>
                                        <button wire:click="delete({{ $dir->id }})" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-base font-semibold shadow transition-all duration-200 flex items-center gap-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>Eliminar</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-[#23232a] rounded-xl p-8 text-center">
                                <p class="text-gray-400 mb-4 text-lg">No tienes direcciones registradas.</p>
                                <button wire:click="nuevaDireccion" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white text-base font-semibold hover:bg-blue-700 transition-all duration-200 shadow-lg">Añadir dirección</button>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Tarjetas de Crédito/Débito -->
                <div class="bg-[#18181b] rounded-2xl p-8 border border-[#23232a] shadow-lg flex flex-col mt-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            Mis Tarjetas
                        </h2>
                        <div>
                            @php $tarjetas = auth()->user()->tarjetas()->get(); @endphp
                            @if($tarjetas->count() < 3)
                                <a href="{{ route('tarjetas') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-600 text-xs font-medium text-gray-200 hover:bg-gray-800 transition-all duration-200"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Nueva</a>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        @if($tarjetas->count() > 0)
                            @foreach($tarjetas->take(2) as $tarjeta)
                                <div class="bg-[#23232a] rounded-xl p-5">
                                    <div class="flex justify-between">
                                        <div class="font-semibold text-white text-base">{{ $tarjeta->nombre_tarjeta }}</div>
                                        @if($tarjeta->es_predeterminada)
                                            <div class="text-xs text-blue-400">Predeterminada</div>
                                        @endif
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <div class="text-gray-400 text-xs">**** **** **** {{ substr($tarjeta->numero_tarjeta, -4) }}</div>
                                        <div class="text-gray-500 text-xs">Vence: {{ $tarjeta->vencimiento }}</div>
                                        <div class="text-gray-500 text-xs">{{ ucfirst($tarjeta->tipo_tarjeta) }}</div>
                                    </div>
                                </div>
                            @endforeach
                            @if($tarjetas->count() > 2)
                                <div class="text-center mt-2">
                                    <a href="{{ route('tarjetas') }}" class="text-gray-400 hover:text-gray-200 text-xs transition-all duration-200">Ver todas las tarjetas</a>
                                </div>
                            @endif
                        @else
                            <div class="bg-[#23232a] rounded-xl p-8 text-center">
                                <p class="text-gray-400 mb-4 text-base">No tienes tarjetas registradas.</p>
                                <a href="{{ route('tarjetas') }}" class="inline-flex items-center justify-center px-5 py-2 rounded-xl border border-gray-600 text-xs font-medium text-gray-200 hover:bg-gray-800 transition-all duration-200">Añadir tarjeta</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #23232a;
        border-radius: 8px;
    }
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #23232a #18181b;
    }
    </style>
</div>
