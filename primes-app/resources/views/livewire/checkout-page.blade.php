<div class="min-h-screen bg-gray-900 text-white">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
		<!-- Cabecera -->
		<div class="text-center mb-12">
			<h1 class="text-4xl font-bold text-white mb-2">
				Finalizar Compra
			</h1>
			<p class="text-lg text-gray-300">
				Completa tu pedido de forma segura
			</p>

			@if (session()->has('error'))
				<div class="mt-4 bg-red-500/20 border border-red-500/50 text-red-400 p-4 rounded-lg">
					{{ session('error') }}
				</div>
			@endif
		</div>

		<!-- Grid principal -->
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Panel izquierdo: Dirección de envío -->
			<div class="lg:col-span-2 space-y-6">
				<!-- Direcciones guardadas -->
				<div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
					<h2 class="text-xl font-bold mb-6 text-blue-400">Dirección de Envío</h2>
					
					@if($direcciones->count() > 0)
						<div class="space-y-4">
							@foreach($direcciones as $dir)
								<div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition-all duration-300">
									<label class="flex items-start gap-4 cursor-pointer">
										<input type="radio" name="direccion" wire:model="direccion_seleccionada" value="{{ $dir->id }}"
											class="mt-1 bg-gray-600 border-gray-500 text-blue-500 focus:ring-blue-500">
										<div class="flex-1">
											<div class="font-medium text-white">{{ $dir->nombre }} {{ $dir->apellido }}</div>
											<div class="text-gray-300 text-sm">{{ $dir->direccion_calle }}</div>
											<div class="text-gray-300 text-sm">{{ $dir->ciudad }}, {{ $dir->estado }} {{ $dir->codigo_postal }}</div>
											<div class="text-gray-300 text-sm">Tel: {{ $dir->telefono }}</div>
										</div>
									</label>
								</div>
							@endforeach
						</div>
					@else
						<div class="text-center py-4">
							<p class="text-gray-300 mb-4">No tienes direcciones guardadas.</p>
						</div>
					@endif

					<div class="mt-6">
						<a href="/mi-cuenta">
							<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
							<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6" />
							</svg>
								Gestionar direcciones en mi cuenta
						</button>
						</a>
					</div>
				</div>

				<!-- Método de pago -->
				<div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
					<h2 class="text-xl font-bold mb-6 text-blue-400">Método de Pago</h2>
					<div class="mb-4">
						<select wire:model.live="metodo_pago" class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
							<option value="">Selecciona un método de pago</option>
							<option value="paypal">PayPal</option>
							<option value="tarjeta">Tarjeta (débito/crédito)</option>
							<option value="pce">Pago contra entrega</option>
						</select>
						@if(isset($errores_pago['metodo_pago']))
							<div class="text-red-400 text-sm mt-1">{{ $errores_pago['metodo_pago'] }}</div>
						@endif
					</div>
					<div class="space-y-4">

						@if($metodo_pago === 'paypal')
							<div class="bg-gray-700/50 rounded-lg p-4 flex flex-col items-center">
								<div id="paypal-button-container"></div>
								<span class="text-xs text-gray-300 mt-2">Serás redirigido a PayPal para completar el pago.</span>
							</div>
						@elseif($metodo_pago === 'tarjeta')
							<!-- Tarjetas guardadas -->
							<div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-6">
								<h2 class="text-xl font-bold mb-6 text-blue-400">Tarjeta de Pago</h2>
								@if(count($tarjetas) > 0)
									<div class="space-y-4">
										@foreach($tarjetas as $tarjeta)
											<div class="bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition-all duration-300">
												<label class="flex items-start gap-4 cursor-pointer">
													<input type="radio" name="tarjeta" wire:model="tarjeta_id" value="{{ $tarjeta->id }}"
														class="mt-1 bg-gray-600 border-gray-500 text-blue-500 focus:ring-blue-500">
													<div class="flex-1">
														<div class="font-medium text-white">{{ $tarjeta->alias ?? 'Tarjeta (***' . substr($tarjeta->numero_tarjeta, -3) . ')' }}</div>
														<div class="text-gray-300 text-sm">***{{ substr($tarjeta->numero_tarjeta, -3) }}</div>
														<div class="text-gray-300 text-sm">Vence: {{ $tarjeta->vencimiento }}</div>
													</div>
												</label>
											</div>
										@endforeach
									</div>
								@else
									<div class="text-center py-4">
										<p class="text-gray-300 mb-4">No tienes tarjetas guardadas.</p>
									</div>
								@endif
								<div class="mt-6">
									<a href="{{ route('cuenta') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
										<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
										</svg>
										Gestionar tarjetas en mi cuenta
									</a>
								</div>
						</div>
						@elseif($metodo_pago === 'pce')
							<div class="bg-gray-700/50 rounded-lg p-4 text-green-400 font-semibold">Pago contra entrega (pagarás al recibir el pedido)</div>
						@endif
					</div>
				</div>
			</div>

			<!-- Panel derecho: Resumen del pedido -->
			<div class="bg-gray-800 rounded-xl p-6 border border-gray-700 h-fit">
				<h2 class="text-xl font-bold mb-6 text-blue-400">Resumen del Pedido</h2>
				
				<div class="space-y-4">
					@foreach($carrito as $item)
						<div class="flex gap-4 py-4 border-b border-gray-700 last:border-0">
							<img src="{{ url('storage', $item->producto->imagenes[0]) }}"
								alt="{{ $item->producto->nombre }}"
								class="w-20 h-20 object-cover rounded-lg bg-gray-700"
								loading="lazy">
							
							<div class="flex-1">
								<h3 class="font-medium text-white">{{ $item->producto->nombre }}</h3>
								<p class="text-sm text-gray-300">Cantidad: {{ $item->cantidad }}</p>
								<p class="text-blue-400 font-bold mt-1">
									RD$ {{ number_format($item->producto->precio * $item->cantidad, 2) }}
								</p>
							</div>
						</div>
					@endforeach

					<!-- Totales -->
					<div class="pt-4 space-y-2">
						<div class="flex justify-between text-gray-300">
							<span>Subtotal</span>
							<span>RD$ {{ number_format($subtotal, 2) }}</span>
						</div>
						<div class="flex justify-between text-gray-300">
							<span>Envío</span>
							<span>RD$ {{ number_format($envio, 2) }}</span>
						</div>
						<div class="flex justify-between text-gray-300">
							<span>ITBIS (18%)</span>
							<span>RD$ {{ number_format($itbis, 2) }}</span>
						</div>
						<div class="flex justify-between text-white font-bold text-lg pt-2 border-t border-gray-700">
							<span>Total</span>
							<span>RD$ {{ number_format($total, 2) }}</span>
						</div>
					</div>

					<!-- Botón de pago -->
					<button wire:click="realizarPedido" 
						class="w-full mt-6 px-6 py-3 border border-transparent rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center gap-2">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
						</svg>
						<span wire:loading.remove>Pagar Ahora</span>
						<span wire:loading>Procesando...</span>
					</button>

					@if (session()->has('error'))
						<div class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
							<p class="text-red-500 text-sm">{{ session('error') }}</p>
						</div>
					@endif

					<p class="text-sm text-gray-400 text-center mt-4">
						Pago seguro
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://www.paypal.com/sdk/js?client-id=ATB8WxGXsSNf6VEv8kzk5E4zt9hYRmsxvPa9XsLkj4MWS5cyIHeNVBwmenHn-AXGzGJ-fMcG_PrIDqOw&currency=USD"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		Livewire.on('pedidoCreado', (pedidoId) => {
			window.location.href = '/success?pedido=' + pedidoId;
		});
	});

	if (document.getElementById('paypal-button-container')) {
		paypal.Buttons({
			createOrder: function(data, actions) {
				return actions.order.create({
					purchase_units: [{
						amount: { value: '{{ $total }}' }
					}]
				});
			},
			onApprove: function(data, actions) {
				return actions.order.capture().then(function(details) {
					window.livewire.emit('paypalPagoExitoso', details);
				});
			}
		}).render('#paypal-button-container');
	}
</script>
@endpush