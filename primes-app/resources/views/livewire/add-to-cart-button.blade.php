<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button wire:click="addToCart" class="cyber-button-small inline-flex items-center px-4 py-2 text-white rounded-lg transition-all duration-300 transform hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4 mr-2" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
        </svg>
        <span>Añadir al Carrito</span>
    </button>
    @if($feedback)
        <div class="mt-2 text-sm text-blue-400 animate-pulse">{{ $feedback }}</div>
    @endif
    <script>
        window.addEventListener('showLoginAlert', () => {
            alert('Debes iniciar sesión para añadir productos al carrito.');
            window.location.href = '/login';
        });
    </script>
</div>
