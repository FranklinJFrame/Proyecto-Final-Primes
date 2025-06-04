<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-white mb-6">Mis Devoluciones</h1>
    @if($devoluciones && count($devoluciones) > 0)
        <div class="space-y-6">
            @foreach($devoluciones as $devolucion)
                <div class="bg-gray-800 rounded-xl p-5 flex items-center gap-6 shadow-lg border border-gray-700">
                    @if($devolucion->pedido && $devolucion->pedido->productos && count($devolucion->pedido->productos) > 0)
                        @php $item = $devolucion->pedido->productos->first(); @endphp
                        @if($item->producto && isset($item->producto->imagenes) && count($item->producto->imagenes) > 0)
                            @php
                                $imgSrc = $item->producto->imagenes[0];
                                $isCloudinary = str_starts_with($imgSrc, 'http');
                            @endphp
                            <img src="{{ $isCloudinary ? $imgSrc : url('storage/' . $imgSrc) }}" alt="{{ $item->producto->nombre }}" class="w-20 h-20 object-cover rounded-md border border-gray-600">
                        @else
                            <div class="w-20 h-20 bg-gray-700 flex items-center justify-center rounded-md">
                                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    @endif
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg font-semibold text-white">#{{ str_pad($devolucion->pedido_id, 8, '0', STR_PAD_LEFT) }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @switch($devolucion->estado)
                                    @case('pendiente') bg-yellow-900/60 text-yellow-400 border border-yellow-500/50 @break
                                    @case('aprobada') bg-green-900/60 text-green-400 border border-green-500/50 @break
                                    @case('rechazada') bg-red-900/60 text-red-400 border border-red-500/50 @break
                                    @default bg-gray-700 text-gray-300 border border-gray-600 @break
                                @endswitch
                            ">
                                {{ ucfirst($devolucion->estado) }}
                            </span>
                        </div>
                        <div class="text-gray-300 text-sm mb-1">{{ $devolucion->created_at->format('d/m/Y H:i') }}</div>
                        @if($devolucion->motivo)
                            <div class="text-gray-400 text-sm"><span class="font-semibold">Motivo:</span> {{ $devolucion->motivo }}</div>
                        @endif
                        @if($devolucion->admin_notes)
                            <div class="text-gray-400 text-sm"><span class="font-semibold">Notas Admin:</span> {{ $devolucion->admin_notes }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-gray-400 text-center py-12">No tienes devoluciones registradas.</div>
    @endif
</div>
