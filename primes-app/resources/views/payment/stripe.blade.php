<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Pagar con Tarjeta</h2>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Resumen del Pedido</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Pedido #{{ $pedido->id }}</span>
                        <span class="font-medium">{{ number_format($pedido->total_general, 2) }} {{ $pedido->moneda }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <form id="payment-form" class="space-y-4">
                    <div>
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                            Información de la Tarjeta
                        </label>
                        <div id="card-element" class="p-3 border rounded-md">
                            <!-- Stripe Elements Placeholder -->
                        </div>
                        <div id="card-errors" role="alert" class="mt-2 text-red-600 text-sm"></div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Pagar {{ number_format($pedido->total_general, 2) }} {{ $pedido->moneda }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');

        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const errorElement = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
            });

            if (error) {
                errorElement.textContent = error.message;
            } else {
                // Aquí deberías enviar el paymentMethod.id a tu servidor
                // y procesar el pago con Stripe
                console.log('PaymentMethod:', paymentMethod);
            }
        });
    </script>
    @endpush
</x-app-layout> 