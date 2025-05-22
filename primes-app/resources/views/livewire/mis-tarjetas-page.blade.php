<div class="min-h-screen bg-gray-900 py-10">
    <div class="max-w-3xl mx-auto bg-gray-800 rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-blue-400 mb-8 flex items-center gap-2">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Mis Tarjetas
        </h2>

        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg">
                <p class="text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg">
                <p class="text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        @if($mostrarFormulario)
            <div class="mb-8">
                <form wire:submit.prevent="{{ $modo === 'crear' ? 'save' : 'update' }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre en la tarjeta -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-300">Nombre en la tarjeta</label>
                            <input wire:model.defer="nombre_tarjeta" type="text" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" required>
                            @error('nombre_tarjeta') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Número de tarjeta -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-300">Número de tarjeta</label>
                            <input wire:model.defer="numero_tarjeta" type="text" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" required>
                            @error('numero_tarjeta') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha de vencimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Fecha de vencimiento (MM/YY)</label>
                            <input wire:model.defer="vencimiento" type="text" placeholder="MM/YY" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" required>
                            @error('vencimiento') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- CVC -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">CVC</label>
                            <input wire:model.defer="cvc" type="text" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" required>
                            @error('cvc') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tipo de tarjeta -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-300">Tipo de tarjeta</label>
                            <select wire:model.defer="tipo_tarjeta" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecciona un tipo</option>
                                <option value="visa">Visa</option>
                                <option value="mastercard">Mastercard</option>
                                <option value="amex">American Express</option>
                            </select>
                            @error('tipo_tarjeta') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Predeterminada -->
                        <div class="col-span-2">
                            <label class="flex items-center space-x-3">
                                <input wire:model.defer="es_predeterminada" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                                <span class="text-gray-300">Establecer como tarjeta predeterminada</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="mb-4">
                @if($tarjetas->count() < 3)
                    <button wire:click="nuevaTarjeta" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Agregar Nueva Tarjeta
                    </button>
                @endif
            </div>
        @endif

        <div class="space-y-4">
            @forelse($tarjetas as $tarjeta)
                <div class="bg-gray-700/50 rounded-lg p-4 border border-gray-600">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-medium text-white">{{ $tarjeta->nombre_tarjeta }}</span>
                                @if($tarjeta->es_predeterminada)
                                    <span class="px-2 py-1 text-xs bg-blue-500/20 text-blue-400 rounded-full">Predeterminada</span>
                                @endif
                            </div>
                            <p class="text-gray-300 mt-1">**** **** **** {{ substr($tarjeta->numero_tarjeta, -4) }}</p>
                            <p class="text-gray-400 text-sm mt-1">Vence: {{ $tarjeta->vencimiento }}</p>
                            <p class="text-gray-400 text-sm">{{ ucfirst($tarjeta->tipo_tarjeta) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $tarjeta->id }})" class="p-2 text-yellow-400 hover:text-yellow-300 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $tarjeta->id }})" class="p-2 text-red-400 hover:text-red-300 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <p class="mt-4 text-gray-400">No tienes tarjetas registradas</p>
                    @if($tarjetas->count() < 3)
                        <button wire:click="nuevaTarjeta" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Primera Tarjeta
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div> 