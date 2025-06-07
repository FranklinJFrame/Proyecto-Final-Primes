<div class="min-h-screen bg-black">
    <!-- Hero Section -->
    <div x-data="parallax()" x-init="initParallax()" class="relative min-h-screen bg-black overflow-hidden pt-16">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <!-- Gradiente animado de fondo -->
            <div class="absolute inset-0 bg-gradient-radial"></div>
            <!-- Grid animada -->
            <div class="absolute inset-0 bg-grid-pattern opacity-20 animate-grid"></div>
            <!-- Líneas de neón -->
            <div class="absolute inset-0 neon-lines"></div>
        </div>
  
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left transform hover:scale-105 transition-transform duration-300 z-20">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-pulse-slow">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 via-purple-500 to-red-500 animate-gradient">
                            Potencia Extrema
                        </span>
                        <br>
                        <span class="text-glow">Para Gaming</span>
                    </h1>
                    <p class="text-lg text-gray-300 mb-8 animate-fade-in">
                        Descubre la nueva MSI GeForce RTX Series. Rendimiento revolucionario, 
                        ray tracing en tiempo real y tecnología de vanguardia para una experiencia 
                        gaming sin precedentes.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ url('/products?categoria=tarjetas-graficas') }}" 
                            class="neon-button inline-flex items-center px-6 py-3 rounded-md text-white transition-all duration-300 transform hover:scale-110">
                             Conoce nuestras nuevas tarjetas gráficas
                             <svg class="ml-2 -mr-1 w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                             </svg>
                         </a>
                    </div>
                </div>
  
                <!-- 3D Card Display -->
                @php
                    // Buscar el producto con id 22 en los destacados o hacer una consulta directa si no está
                    $productoPortada = $destacados->firstWhere('id', 22) ?? \App\Models\Producto::find(22);
                @endphp
                <div class="relative group perspective-1000 h-[800px] parallax-container">
                    <a href="{{ url('/products?categoria=tarjetas-graficas') }}">
                    <div class="relative transform transition-all duration-700 ease-out hover:rotate-y-12 card-container h-full"
                         x-ref="parallaxImage"
                         @scroll.window="handleScroll()">
                        <img src="https://storage-asset.msi.com/global/picture/image/feature/vga/NVIDIA/GB203-400-Gaming-trio-white/images/kv_vga-2.png" 
                             alt="MSI Graphics Card" 
                             loading="lazy"
                             class="w-full h-full object-contain transform transition-all duration-700 hover:scale-110 animate-float card-image parallax-image"
                             style="filter: drop-shadow(0 0 3rem rgba(59, 130, 246, 0.7));">
                        
                        <!-- Efectos de brillo -->
                        <div class="absolute inset-0 card-glow opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <!-- Specs flotantes con efecto cyber -->
                        <div class="absolute -right-4 top-1/4 transform translate-x-full opacity-0 group-hover:opacity-100 transition-all duration-500 cyber-panel">
                            <div class="text-white space-y-2 p-6">
                                <p class="font-semibold text-blue-400">RTX 5080</p>
                                <p class="text-sm text-blue-300">24GB GDDR6X</p>
                                <p class="text-sm text-blue-300">DLSS 3.0</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
  
        <!-- Floating Elements with Cyber Effect -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex gap-8">
            <div class="cyber-tag flex items-center space-x-2 text-white/80">
                <svg class="w-6 h-6 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Ray Tracing</span>
            </div>
            <div class="cyber-tag flex items-center space-x-2 text-white/80">
                <svg class="w-6 h-6 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>DLSS 3.0</span>
            </div>
            <div class="cyber-tag flex items-center space-x-2 text-white/80">
                <svg class="w-6 h-6 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>MSI Center</span>
            </div>
        </div>
    </div>
  
    <!-- Categorías Principales -->
    <div class="bg-black py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-white mb-12 cyber-glitch-text">
                Categorías Gaming
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($categorias as $categoria)
                <a href="/categoria/{{ $categoria->slug }}" class="cyber-category-card group block hover:shadow-blue-500/30 hover:scale-105 transition-all duration-300">
                    <div class="relative overflow-hidden rounded-xl">
                        <div class="flex items-center justify-center w-full h-48 bg-white shadow-inner rounded-xl p-4">
                            <img src="{{ $categoria->imagen ? url('storage/'.$categoria->imagen) : 'https://via.placeholder.com/300x200' }}" 
                                 alt="{{ $categoria->nombre }}" 
                                 loading="lazy"
                                 class="max-h-32 max-w-full object-contain">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent pointer-events-none"></div>
                        <div class="absolute bottom-4 left-4">
                            <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition">{{ $categoria->nombre }}</h3>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    
            
<!-- Accesorios Esenciales -->
<div class="bg-black py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <h2 class="text-3xl font-bold text-center text-white mb-12 cyber-glitch-text">
                Marcas
            </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      @foreach($marcas as $marca)
      <a href="/marca/{{ $marca->slug }}" class="cyber-category-card group block hover:shadow-blue-500/30 hover:scale-105 transition-all duration-300">
        <div class="relative overflow-hidden rounded-xl">
          <div class="flex items-center justify-center w-full h-48 bg-white shadow-inner rounded-xl p-4">
              <img src="{{ $marca->imagen ? url('storage/'.$marca->imagen) : 'https://via.placeholder.com/300x200' }}" 
                   alt="{{ $marca->nombre }}" 
                   loading="lazy"
                   class="max-h-32 max-w-full object-contain">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent pointer-events-none"></div>
          <div class="absolute bottom-4 left-4">
            <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition">{{ $marca->nombre }}</h3>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>


            
    <!-- Ofertas Destacadas -->
    <div class="bg-gradient-to-b from-black via-blue-900/20 to-black py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-white cyber-glitch-text">
                    Ofertas Gaming
                </h2>
                <a href="/productos?oferta=1" class="cyber-button-small px-6 py-2 text-white rounded-lg">
                    Ver todas
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($ofertas as $oferta)
                <a href="/products/{{ $oferta->slug }}" class="block group">
                <div class="cyber-product-card bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900/60 border-2 border-blue-500/30 hover:border-blue-500 shadow-xl hover:shadow-blue-500/30 transition-all duration-300 rounded-2xl overflow-hidden">
                    <div class="relative">
                        @if($oferta->descuento ?? false)
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                            -{{ $oferta->descuento }}%
                        </div>
                        @endif
                        <img src="{{ (isset($oferta->imagenes[0]) && $oferta->imagenes[0]) ? url('storage/'.$oferta->imagenes[0]) : 'https://via.placeholder.com/300x200' }}" alt="{{ $oferta->nombre }}" loading="lazy" class="w-full h-56 object-contain bg-gray-900 group-hover:scale-105 transition-transform duration-300 rounded-t-2xl">
                    </div>
                    <div class="p-4 flex flex-col gap-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition">{{ $oferta->nombre }}</h3>
                        <p class="text-gray-400 text-xs mb-2 line-clamp-2">{{ $oferta->descripcion }}</p>
                        <div class="flex justify-between items-center mt-auto">
                            <div>
                                @if($oferta->precio_original ?? false)
                                <span class="text-gray-400 line-through text-sm">RD$ {{ number_format($oferta->precio_original, 2) }}</span>
                                @endif
                                <span class="text-xl font-bold text-blue-400 ml-2">RD$ {{ number_format($oferta->precio, 2) }}</span>
                            </div>
                            <span class="inline-block px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-bold">En oferta</span>
                        </div>
                    </div>
                </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
  
    <!-- Featured Products Section -->
    <div class="bg-black py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-white mb-12 cyber-glitch-text">
                Productos Destacados
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($destacados as $producto)
                <div class="cyber-card bg-gray-900 rounded-xl overflow-hidden hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-500 transform hover:-translate-y-4 hover:scale-105">
                    <a href="/products/{{ $producto->slug }}" class="block">
                        <div class="relative overflow-hidden">
                            @if($producto->id == 19)
                                <img src="https://asset.msi.com/resize/image/global/product/product_1663742002e0bd21ebc380d0ea907a5374ca6919f8.png62405b38c58fe0f07fcef2367d8a9ba1/600.png" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-48 object-contain transform transition-transform duration-500 hover:scale-110">
                            @elseif($producto->id == 20)
                                <img src="https://storage-asset.msi.com/global/picture/image/feature/vga/NVIDIA/VGA-2020/image/KV-3080X.png" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-48 object-contain transform transition-transform duration-500 hover:scale-110">
                            @elseif($producto->id == 21)
                                <img src="https://asset.msi.com/resize/image/global/product/product_16104439091f604cc0c971b267b62a1ce937d362d0.png62405b38c58fe0f07fcef2367d8a9ba1/600.png" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-48 object-contain transform transition-transform duration-500 hover:scale-110">
                            @else
                                <img src="{{ isset($producto->imagenes[0]) ? url('storage/'.$producto->imagenes[0]) : 'https://via.placeholder.com/300x200' }}" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-48 object-contain transform transition-transform duration-500 hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                        </div>
                        <div class="p-6 relative">
                            <div class="cyber-line"></div>
                            <h3 class="text-xl font-semibold text-white mb-2">{{ $producto->nombre }}</h3>
                            <p class="text-gray-400 mb-4 line-clamp-2">{{ $producto->descripcion }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-white cyber-price">RD$ {{ number_format($producto->precio, 2) }}</span>
                                <span class="cyber-button-small px-4 py-2 text-white rounded-lg transition-all duration-300 transform hover:scale-105">Ver Detalles</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
  
    <!-- Features Section con estilo cyber -->
    <div class="bg-black py-20 relative overflow-hidden">
        <div class="absolute inset-0 cyber-grid opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4 cyber-glitch-text">Tecnología de Última Generación</h2>
                <p class="text-blue-400">Descubre las características que hacen únicas a nuestras tarjetas gráficas</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature Cards con estilo cyber -->
                <div class="cyber-feature-card bg-gray-900/50 p-6 rounded-xl border border-blue-500/30 backdrop-blur-sm hover:border-blue-500 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4 cyber-icon">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Ray Tracing</h3>
                    <p class="text-gray-400">Iluminación y reflejos realistas en tiempo real para una inmersión total</p>
                </div>
  
                <div class="cyber-feature-card bg-gray-900/50 p-6 rounded-xl border border-purple-500/30 backdrop-blur-sm hover:border-purple-500 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4 cyber-icon">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">DLSS 3.0</h3>
                    <p class="text-gray-400">Tecnología de IA que multiplica el rendimiento sin sacrificar calidad visual</p>
                </div>
  
                <div class="cyber-feature-card bg-gray-900/50 p-6 rounded-xl border border-red-500/30 backdrop-blur-sm hover:border-red-500 transition-all duration-300">
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center mb-4 cyber-icon">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">MSI Center</h3>
                    <p class="text-gray-400">Control total sobre tu hardware con nuestra suite de software exclusiva</p>
                </div>
            </div>
        </div>
    </div>
  
  <style>
    /* Animaciones base */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
  
    @keyframes grid {
        0% { transform: translateX(0) translateY(0); }
        100% { transform: translateX(-100%) translateY(-100%); }
    }
  
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); }
        100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
    }
  
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
  
    /* Clases de animación */
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
  
    .animate-grid {
        animation: grid 20s linear infinite;
    }
  
    .animate-pulse-slow {
        animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
  
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 8s ease infinite;
    }
  
    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }
  
    /* Estilos Cyber */
    .bg-gradient-radial {
        background: radial-gradient(circle at center, rgba(59, 130, 246, 0.1) 0%, rgba(0, 0, 0, 1) 70%);
        animation: pulse-glow 4s ease-in-out infinite;
    }
  
    .cyber-grid {
        background-image: linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
                         linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
    }
  
    .neon-button {
        background: linear-gradient(45deg, #00f2fe, #4facfe);
        box-shadow: 0 0 15px rgba(79, 172, 254, 0.5);
    }
  
    .cyber-button {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        box-shadow: inset 0 0 10px rgba(59, 130, 246, 0.2);
    }
  
    .cyber-button:hover {
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: inset 0 0 20px rgba(59, 130, 246, 0.3),
                   0 0 15px rgba(59, 130, 246, 0.3);
    }
  
    .cyber-panel {
        background: rgba(0, 0, 0, 0.8);
        border: 1px solid rgba(59, 130, 246, 0.3);
        backdrop-filter: blur(4px);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
    }
  
    .cyber-tag {
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(59, 130, 246, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        backdrop-filter: blur(4px);
    }
  
    .text-glow {
        text-shadow: 0 0 10px rgba(59, 130, 246, 0.5),
                    0 0 20px rgba(59, 130, 246, 0.3),
                    0 0 30px rgba(59, 130, 246, 0.2);
    }
  
    .cyber-card {
        position: relative;
        overflow: hidden;
    }
  
    .cyber-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
    }
  
    .cyber-price {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        -webkit-background-clip: text;
        color: transparent;
    }
  
    .cyber-button-small {
        background: linear-gradient(45deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));
        border: 1px solid rgba(79, 172, 254, 0.3);
    }
  
    .cyber-feature-card {
        transform-style: preserve-3d;
        transition: all 0.3s ease;
    }
  
    .cyber-feature-card:hover {
        transform: translateZ(20px);
    }
  
    .cyber-icon {
        position: relative;
    }
  
    .cyber-icon::after {
        content: '';
        position: absolute;
        inset: -5px;
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: inherit;
        animation: pulse 2s infinite;
    }
  
    .perspective-1000 {
        perspective: 1000px;
    }
  
    .card-container {
        transform-style: preserve-3d;
    }
  
    .card-image {
        backface-visibility: hidden;
    }
  
    .card-glow {
        background: radial-gradient(circle at 50% 50%, 
            rgba(59, 130, 246, 0.4) 0%,
            rgba(59, 130, 246, 0.1) 50%,
            transparent 100%);
    }
  
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
  
    /* Parallax y efectos de scroll */
    .parallax-container {
        overflow: visible;
        position: relative;
    }
  
    .parallax-image {
        transform-style: preserve-3d;
        will-change: transform;
        transition: transform 0.5s ease-out;
    }
  
    .cyber-category-card {
        position: relative;
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
  
    .cyber-category-card:hover {
        border-color: rgba(59, 130, 246, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }
  
    .cyber-product-card {
        background: rgba(17, 24, 39, 0.7);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }
  
    .cyber-product-card:hover {
        border-color: rgba(59, 130, 246, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }
  </style>
  
  <script>
    function parallax() {
        return {
            lastScrollTop: 0,
            initParallax() {
                this.handleScroll();
            },
            handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const parallaxImage = this.$refs.parallaxImage;
                
                if (parallaxImage) {
                    const translateY = (scrollTop * 0.3);
                    const scale = 1 + (scrollTop * 0.0003);
                    const rotate = (scrollTop * 0.01);
                    
                    parallaxImage.style.transform = `
                        translateY(${translateY}px) 
                        scale(${Math.min(scale, 1.3)}) 
                        rotate(${rotate}deg)
                    `;
                }
                
                this.lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }
        }
    }
  </script>
  </div>