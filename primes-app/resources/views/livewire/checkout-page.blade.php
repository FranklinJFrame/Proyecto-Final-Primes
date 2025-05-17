<div class="min-h-screen bg-black py-10">
	<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
		<!-- Formulario de dirección y pago -->
		<div class="md:w-2/3 w-full bg-gray-900/90 rounded-2xl shadow-xl p-8 border border-blue-500/20">
			<h2 class="text-3xl font-bold text-white mb-8 cyber-glitch-text">Finalizar Compra</h2>
			<form class="flex flex-col gap-6">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Nombre</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Nombre">
					</div>
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Apellido</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Apellido">
					</div>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Teléfono</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Teléfono">
					</div>
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Ciudad</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Ciudad">
					</div>
				</div>
				<div>
					<label class="block text-blue-400 font-semibold mb-2">Dirección</label>
					<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Dirección completa">
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Estado</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="Estado">
					</div>
					<div>
						<label class="block text-blue-400 font-semibold mb-2">Código Postal</label>
						<input type="text" class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none" placeholder="ZIP">
					</div>
				</div>
				<div>
					<label class="block text-blue-400 font-semibold mb-2">Método de Pago</label>
					<select class="w-full rounded-lg border border-blue-500/20 bg-gray-800 text-white px-4 py-3 focus:border-blue-500 outline-none">
						<option>Efectivo contra entrega</option>
						<option>Tarjeta de crédito/débito</option>
						<option>Stripe</option>
					</select>
				</div>
				<button type="submit" class="w-full py-4 rounded-lg bg-blue-600 text-xl font-bold text-white hover:bg-blue-700 transition-all shadow-lg mt-4">Realizar Pedido</button>
			</form>
		</div>
		<!-- Resumen de pedido -->
		<div class="md:w-1/3 w-full">
			<div class="bg-gray-900/90 rounded-2xl shadow-xl border border-blue-500/20 p-8 sticky top-10">
				<h3 class="text-2xl font-bold text-white mb-6 border-b border-blue-500/20 pb-2">Resumen del Pedido</h3>
				<div class="flex flex-col gap-4 mb-6">
					<!-- Ejemplo de producto, reemplaza con tu loop -->
					<div class="flex items-center gap-4 bg-gray-800/80 rounded-xl p-4 border border-blue-500/10">
						<img src="https://via.placeholder.com/80" class="w-16 h-16 object-contain rounded-lg bg-gray-900 border border-blue-500/10" alt="Producto">
						<div class="flex-1">
							<div class="text-white font-semibold">Product name</div>
							<div class="text-blue-400">Cantidad: 1</div>
						</div>
						<div class="text-blue-400 font-bold text-lg">RD$ 19.99</div>
					</div>
					<!-- Fin ejemplo de producto -->
				</div>
				<div class="flex justify-between mb-3 text-lg">
					<span class="text-gray-300">Subtotal</span>
					<span class="text-blue-400 font-semibold">RD$ 19.99</span>
				</div>
				<div class="flex justify-between mb-3 text-lg">
					<span class="text-gray-300">Impuestos</span>
					<span class="text-blue-400 font-semibold">RD$ 1.99</span>
				</div>
				<div class="flex justify-between mb-4 text-lg">
					<span class="text-gray-300">Envío</span>
					<span class="text-blue-400 font-semibold">RD$ 0.00</span>
				</div>
				<div class="border-t border-blue-500/20 my-4"></div>
				<div class="flex justify-between mb-6 text-xl font-bold">
					<span class="text-white">Total</span>
					<span class="text-blue-400">RD$ 21.98</span>
				</div>
			</div>
		</div>
	</div>
	<style>
		.cyber-glitch-text {
			position: relative;
			text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
						-0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
						0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
			animation: glitch 500ms infinite;
		}
		@keyframes glitch {
			0% {
				text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
							-0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
							-0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
			}
			14% {
				text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
							-0.05em -0.025em 0 rgba(0, 255, 0, 0.75),
							-0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
			}
			15% {
				text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
							0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
							-0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
			}
			49% {
				text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75),
							0.025em 0.025em 0 rgba(0, 255, 0, 0.75),
							-0.05em -0.05em 0 rgba(0, 0, 255, 0.75);
			}
			50% {
				text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
							0.05em 0 0 rgba(0, 255, 0, 0.75),
							0 -0.05em 0 rgba(0, 0, 255, 0.75);
			}
			99% {
				text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75),
							0.05em 0 0 rgba(0, 255, 0, 0.75),
							0 -0.05em 0 rgba(0, 0, 255, 0.75);
			}
			100% {
				text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75),
							-0.025em -0.025em 0 rgba(0, 255, 0, 0.75),
							-0.025em -0.05em 0 rgba(0, 0, 255, 0.75);
			}
		}
	</style>
</div>