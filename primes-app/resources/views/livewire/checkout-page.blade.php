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
						<button wire:click="nuevaDireccion" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
							<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
							</svg>
							Agregar nueva dirección
						</button>
					</div>
				</div>

				<!-- Método de pago -->
				<div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
					<h2 class="text-xl font-bold mb-6 text-blue-400">Método de Pago</h2>
					
					<div class="space-y-4">
						<div class="bg-gray-700/50 rounded-lg p-4">
							<div id="card-element" class="bg-gray-600 rounded-md p-4 border border-gray-500"></div>
							<div id="card-errors" class="text-red-400 text-sm mt-2"></div>
						</div>
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
								onerror="this.src='/img/default-product.jpg'">
							
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
						<div class="flex justify-between text-white font-bold text-lg pt-2 border-t border-gray-700">
							<span>Total</span>
							<span>RD$ {{ number_format($total, 2) }}</span>
						</div>
					</div>

					<!-- Botón de pago -->
					<button wire:click="procesarPago" 
						class="w-full mt-6 px-6 py-3 border border-transparent rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center gap-2">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
						</svg>
						Pagar Ahora
					</button>

					<p class="text-sm text-gray-400 text-center mt-4">
						Pago seguro procesado por Stripe
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
	const stripe = Stripe('{{ config('services.stripe.key') }}');
	const elements = stripe.elements();
	const card = elements.create('card', {
		style: {
			base: {
				color: '#fff',
				fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
				fontSmoothing: 'antialiased',
				fontSize: '16px',
				'::placeholder': {
					color: '#aab7c4'
				},
				backgroundColor: '#374151',
			},
			invalid: {
				color: '#fa755a',
				iconColor: '#fa755a'
			}
		}
	});

	card.mount('#card-element');

	card.addEventListener('change', function(event) {
		const displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});
</script>
@endpush