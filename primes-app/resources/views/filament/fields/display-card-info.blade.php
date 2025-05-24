@php
    $tarjeta = $getRecord()->pedido->user->defaultDatosTarjeta ?? $getRecord()->pedido->user->anyDatosTarjeta;
@endphp

@if ($tarjeta)
    <div class="space-y-1 text-sm">
        <p><strong>Tipo Tarjeta:</strong> {{ e($tarjeta->tipo_tarjeta) }}</p>
        <p><strong>Terminación:</strong> {{ e($tarjeta->numero_tarjeta_ofuscado) }}</p>
        <p><strong>Nombre en Tarjeta:</strong> {{ e($tarjeta->nombre_tarjeta) }}</p>
        {{-- No mostrar CVC ni vencimiento --}}
    </div>
@else
    <p class="text-sm text-gray-500">No se encontró información de tarjeta guardada para este usuario.</p>
@endif 