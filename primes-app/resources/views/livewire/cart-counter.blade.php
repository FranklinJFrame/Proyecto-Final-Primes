

<span class="relative">
    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    @if($count > 0)
        <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5 shadow animate-bounce">{{ $count }}</span>
    @endif
</span>
