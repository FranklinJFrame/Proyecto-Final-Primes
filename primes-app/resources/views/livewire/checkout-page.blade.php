<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-200 py-10">
	<div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-10">
		<!-- Formulario de dirección y pago -->
		<div class="md:w-2/3 w-full bg-white rounded-2xl shadow-lg border border-blue-100 p-10 flex flex-col justify-center">
			<h2 class="text-3xl font-extrabold text-blue-700 mb-8 flex items-center gap-2">
				<svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
				Finalizar Compra
			</h2>
			<form wire:submit.prevent="realizarPedido" class="flex flex-col gap-8">
				<!-- Dirección de envío -->
				<div class="flex flex-col gap-5">
					<label class="block text-blue-600 text-base font-bold mb-2">Dirección de envío</label>
					@if($direcciones && $direcciones->count() && !$crear_nueva && !$editando_direccion)
						<div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 flex items-center gap-6 shadow-sm">
							<svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 14v7m0 0H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-7z" /></svg>
							<div class="flex-1">
								<div class="font-bold text-lg text-blue-800">{{ $direcciones->firstWhere('id', $direccion_id) ? $direcciones->firstWhere('id', $direccion_id)->nombre . ' ' . $direcciones->firstWhere('id', $direccion_id)->apellido : '' }}</div>
								<div class="text-gray-600 text-sm mt-1">{{ $direcciones->firstWhere('id', $direccion_id) ? $direcciones->firstWhere('id', $direccion_id)->direccion_calle . ', ' . $direcciones->firstWhere('id', $direccion_id)->ciudad . ', ' . $direcciones->firstWhere('id', $direccion_id)->estado . ', ' . $direcciones->firstWhere('id', $direccion_id)->codigo_postal : '' }}</div>
								<div class="text-gray-400 text-xs mt-1">Tel: {{ $direcciones->firstWhere('id', $direccion_id) ? $direcciones->firstWhere('id', $direccion_id)->telefono : '' }}</div>
							</div>
							<div class="flex flex-col gap-2">
								<button type="button" wire:click="editarDireccion({{ $direccion_id }})" class="px-4 py-2 rounded-lg bg-yellow-400 text-white font-semibold hover:bg-yellow-500 transition">Editar</button>
								<button type="button" wire:click="$set('crear_nueva', true); $set('editando_direccion', false);" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition mt-1">+ Nueva dirección</button>
							</div>
						</div>
					@else
						<div class="bg-white border-2 border-blue-200 rounded-2xl p-8 shadow-lg flex flex-col gap-6">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Nombre</label>
										<input wire:model.defer="nombre" type="text" placeholder="Nombre" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Apellido</label>
										<input wire:model.defer="apellido" type="text" placeholder="Apellido" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Teléfono</label>
										<input wire:model.defer="telefono" type="text" placeholder="Teléfono" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5a2 2 0 00-2-2H6a2 2 0 00-2 2v7c0 6 8 10 8 10z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Ciudad</label>
										<input wire:model.defer="ciudad" type="text" placeholder="Ciudad" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2 md:col-span-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M17 8V6a4 4 0 00-8 0v2m8 0a4 4 0 01-8 0m8 0H7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Dirección</label>
										<input wire:model.defer="direccion_calle" type="text" placeholder="Dirección" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7v4a1 1 0 001 1h3m10-5h3a1 1 0 011 1v4a1 1 0 01-1 1h-3m-10 0h10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Estado</label>
										<input wire:model.defer="estado" type="text" placeholder="Estado" class="input-form bg-blue-50/60" required>
									</div>
								</div>
								<div class="flex items-center gap-2">
									<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									<div class="w-full">
										<label class="block text-gray-500 text-xs mb-1">Código Postal</label>
										<input wire:model.defer="codigo_postal" type="text" placeholder="Código Postal" class="input-form bg-blue-50/60" required>
									</div>
								</div>
							</div>
							<div class="flex gap-2 mt-2">
								@if($direcciones && $direcciones->count())
									<button type="button" wire:click="$set('crear_nueva', false); $set('editando_direccion', false);" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 font-semibold hover:bg-gray-200 transition text-sm w-32">Cancelar</button>
								@endif
								<button type="button" wire:click="$set('crear_nueva', true); $set('editando_direccion', false);" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition text-sm">+ Nueva dirección</button>
								@if($editando_direccion)
									<button type="button" wire:click="$set('editando_direccion', false)" class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-700 font-semibold hover:bg-yellow-200 transition text-sm">Salir de edición</button>
								@endif
							</div>
						</div>
					@endif
				</div>

				<!-- Método de Pago -->
				<div class="flex flex-col gap-2 mt-4">
					<label class="block text-blue-600 text-base font-bold mb-2">Método de Pago</label>
					<select wire:model="metodo_pago" class="w-full rounded-lg border border-blue-200 bg-white text-gray-800 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
						<option value="efectivo">Efectivo contra entrega</option>
						<option value="tarjeta">Tarjeta de crédito/débito</option>
						<option value="stripe">Stripe (Simulación)</option>
					</select>
				</div>

				@if($stripeCard)
				<!-- Simulación de tarjeta -->
				<div id="stripe-card-section" class="flex flex-col md:flex-row gap-6 bg-gradient-to-r from-blue-50 to-blue-100 border-2 border-blue-200 rounded-2xl p-6 shadow-lg mt-2 relative overflow-hidden">
					<div class="flex-1 flex flex-col justify-center">
						<div class="mb-4">
							<label class="block text-gray-600 text-xs mb-1">Número de tarjeta</label>
							<div class="relative">
								<input type="text" placeholder="4242 4242 4242 4242" class="input-form pl-12 font-mono tracking-widest text-lg bg-white/80" maxlength="19">
								<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="2" fill="#e0e7ff"/><rect x="2" y="7" width="20" height="10" rx="2" stroke="#3b82f6" stroke-width="2"/><rect x="6" y="13" width="4" height="2" rx="1" fill="#3b82f6"/></svg>
							</div>
						</div>
						<div class="flex gap-4 mb-4">
							<div class="flex-1">
								<label class="block text-gray-600 text-xs mb-1">MM/AA</label>
								<input type="text" placeholder="12/34" class="input-form bg-white/80" maxlength="5">
							</div>
							<div class="flex-1">
								<label class="block text-gray-600 text-xs mb-1">CVC</label>
								<input type="text" placeholder="123" class="input-form bg-white/80" maxlength="4">
							</div>
						</div>
						@if($stripeError)
							<div class="text-red-500 text-sm font-semibold rounded bg-red-50 border border-red-200 p-2">{{ $stripeError }}</div>
						@endif
						<div class="text-xs text-gray-400 mt-2">* Simulación visual. No se realiza ningún cobro real.</div>
					</div>
					<div class="hidden md:block md:w-1/3 flex-shrink-0 relative">
						<img src="https://media.istockphoto.com/id/531236924/es/foto/grupo-de-tarjetas-de-cr%C3%A9dito-en-computadora-teclado.jpg?s=612x612&w=0&k=20&c=6qdn99y-lMRdzssB1mE--S-jVHLZYZ4PhrHIqDZtoUE=" alt="Tarjetas" class="rounded-xl shadow-lg object-cover w-full h-40 border-2 border-blue-200">
					</div>
				</div>
				@endif

				<button type="submit" class="w-full py-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-xl font-bold text-white hover:from-blue-700 hover:to-blue-600 transition-all shadow-lg mt-8">Realizar Pedido</button>
				@if(session('error'))
					<div class="text-red-500 font-semibold mt-2">{{ session('error') }}</div>
				@endif
			</form>
			<style>
				.input-form {
					@apply w-full px-4 py-3 rounded-lg border border-blue-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white text-gray-800 text-base shadow-sm;
				}
			</style>
		</div>
		<!-- Resumen de pedido -->
		<div class="md:w-1/3 w-full">
			<div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-8 sticky top-10">
				<h3 class="text-2xl font-bold text-blue-700 mb-6 border-b border-blue-200 pb-2 flex items-center gap-2">
					<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
					Resumen
				</h3>
				<div class="flex flex-col gap-4 mb-6">
					@foreach($carrito as $item)
					<div class="flex items-center gap-4 bg-blue-50 rounded-xl p-4 border border-blue-100">
						<img src="{{ 
							$item->producto->imagenes && is_array($item->producto->imagenes) && count($item->producto->imagenes) > 0
								? (filter_var($item->producto->imagenes[0], FILTER_VALIDATE_URL)
									? $item->producto->imagenes[0]
									: asset('storage/products/' . $item->producto->imagenes[0]))
								: 'https://placehold.co/80x80/png?text=Sin+Imagen'
						}}" class="w-16 h-16 object-contain rounded-lg bg-gray-100 border border-gray-200" alt="Imagen del producto">
						<div class="flex-1 min-w-0">
							<div class="text-blue-800 font-semibold truncate">{{ $item->producto->nombre }}</div>
							<div class="text-gray-500 text-xs mt-1">Cantidad: {{ $item->cantidad }}</div>
						</div>
						<div class="text-blue-600 font-bold text-lg whitespace-nowrap">RD$ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
					</div>
					@endforeach
				</div>
				<div class="flex justify-between mb-3 text-base">
					<span class="text-gray-500">Subtotal</span>
					<span class="text-blue-800 font-semibold">RD$ {{ number_format($subtotal, 2) }}</span>
				</div>
				<div class="flex justify-between mb-3 text-base">
					<span class="text-gray-500">ITBIS (18%)</span>
					<span class="text-blue-800 font-semibold">RD$ {{ number_format($itbis, 2) }}</span>
				</div>
				<div class="flex justify-between mb-3 text-base">
					<span class="text-gray-500">Envío</span>
					<span class="text-blue-800 font-semibold">RD$ {{ number_format($envio, 2) }}</span>
				</div>
				<div class="border-t border-blue-200 my-4"></div>
				<div class="flex justify-between mb-6 text-lg font-bold">
					<span class="text-blue-800">Total</span>
					<span class="text-blue-600">RD$ {{ number_format($total, 2) }}</span>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Stripe: mostrar automáticamente -->
<script>
	document.addEventListener('livewire:load', function () {
		Livewire.on('stripeSelected', () => {
			setTimeout(() => {
				document.getElementById('stripe-card-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
			}, 100);
		});
	});
</script>