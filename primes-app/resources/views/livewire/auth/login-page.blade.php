<div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900/20 to-black relative overflow-hidden">
  <style>
      .glass-effect {
          background: rgba(0, 0, 0, 0.7);
          backdrop-filter: blur(20px);
          border: 1px solid rgba(255, 255, 255, 0.1);
          box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
          position: relative;
          z-index: 10;
      }

      .tech-input {
          background: rgba(0, 0, 0, 0.5);
          border: 1px solid rgba(59, 130, 246, 0.3);
          transition: all 0.3s ease;
          position: relative;
          z-index: 15;
      }

      .tech-input:focus {
          border-color: #3b82f6;
          box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
      }

      .gradient-border {
          position: relative;
          background: linear-gradient(#000, #000) padding-box,
                      linear-gradient(90deg, #3b82f6, #8b5cf6) border-box;
          border: 1px solid transparent;
      }

      .animated-bg {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          z-index: 1;
          background: 
              radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
              radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
          filter: blur(80px);
          animation: pulse 8s ease infinite alternate;
          pointer-events: none;
      }

      @keyframes pulse {
          0% { opacity: 0.5; }
          100% { opacity: 1; }
      }

      .tech-lines {
          position: fixed;
          inset: 0;
          z-index: 2;
          pointer-events: none;
          background-image: 
              linear-gradient(to right, rgba(59, 130, 246, 0.1) 1px, transparent 1px),
              linear-gradient(to bottom, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
          background-size: 30px 30px;
          mask-image: radial-gradient(circle at center, black 30%, transparent 70%);
      }

      .glow-effect {
          position: relative;
      }

      .glow-effect::after {
          content: '';
          position: absolute;
          inset: -1px;
          background: linear-gradient(90deg, #3b82f6, #8b5cf6);
          filter: blur(12px);
          opacity: 0;
          transition: opacity 0.3s ease;
          z-index: -1;
          border-radius: inherit;
      }

      .glow-effect:hover::after {
          opacity: 0.5;
      }

      .floating-elements {
          position: fixed;
          inset: 0;
          z-index: 1;
          overflow: hidden;
          pointer-events: none;
      }

      .floating-element {
          position: absolute;
          background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
          border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
          filter: blur(3px);
      }
  </style>

  <!-- Elementos flotantes de fondo -->
  <div class="floating-elements">
      <div class="floating-element w-96 h-96 -top-20 -left-20 animate-[spin_20s_linear_infinite]"></div>
      <div class="floating-element w-96 h-96 -bottom-20 -right-20 animate-[spin_25s_linear_infinite_reverse]"></div>
  </div>

  <!-- Fondo animado -->
  <div class="animated-bg"></div>
  <div class="tech-lines opacity-20"></div>

  <div class="flex min-h-screen items-center justify-center px-4 py-12 relative">
      <div class="w-full max-w-md relative">
          <!-- Logo y encabezado con efecto de brillo -->
          <div class="text-center mb-8">
              <a href="/" class="inline-block mb-4 glow-effect">
                  <h1 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-500">
                      TECNO<span class="text-white">BOX</span>
                  </h1>
              </a>
              <p class="text-blue-300/80">Accede a las mejores ofertas en tecnología</p>
          </div>

          <!-- Tarjeta de login -->
          <div class="glass-effect rounded-2xl p-8 shadow-2xl relative">
              <!-- Decoración de esquinas -->
              <div class="absolute top-0 left-0 w-16 h-16 border-t-2 border-l-2 border-blue-500/30 rounded-tl-2xl"></div>
              <div class="absolute bottom-0 right-0 w-16 h-16 border-b-2 border-r-2 border-purple-500/30 rounded-br-2xl"></div>

              <div class="mb-6">
                  <h2 class="text-2xl font-semibold text-white mb-1">Iniciar sesión</h2>
                  <p class="text-blue-300/80 text-sm">Bienvenido de vuelta</p>
              </div>

              <form wire:submit="login" class="space-y-6">
                  <!-- Email -->
                  <div class="space-y-2">
                      <label class="block text-sm font-medium text-gray-300 mb-1 ml-1">
                          Correo electrónico
                      </label>
                      <div class="relative group">
                          <input wire:model="email" type="email" 
                                 class="tech-input w-full px-5 py-3 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none
                                        transition-all duration-300 group-hover:border-blue-400"
                                 placeholder="tu@email.com">
                          <div class="absolute inset-0 rounded-xl transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                               style="background: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 100%);">
                          </div>
                      </div>
                      @error('email')
                          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                      @enderror
                  </div>

                  <!-- Contraseña -->
                  <div class="space-y-2">
                      <label class="block text-sm font-medium text-gray-300 mb-1 ml-1">
                          Contraseña
                      </label>
                      <div class="relative group">
                          <input wire:model="password" type="password" 
                                 class="tech-input w-full px-5 py-3 rounded-xl text-gray-200 placeholder-gray-500 focus:outline-none
                                        transition-all duration-300 group-hover:border-blue-400"
                                 placeholder="Tu contraseña">
                          <div class="absolute inset-0 rounded-xl transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                               style="background: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 100%);">
                          </div>
                      </div>
                      @error('password')
                          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                      @enderror
                  </div>

                  <!-- Remember me y Recuperar contraseña -->
                  <div class="flex items-center justify-between">
                      <div class="flex items-center">
                          <input wire:model="remember" type="checkbox" 
                                 class="rounded border-blue-500/30 bg-black/50 text-blue-500 focus:ring-blue-500/40">
                          <label class="ml-2 text-sm text-gray-400">Recordarme</label>
                      </div>
                      <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-all duration-300 hover:tracking-wide">
                          ¿Olvidaste tu contraseña?
                      </a>
                  </div>

                  <!-- Login button -->
                  <button type="submit" 
                          class="glow-effect w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600
                                 text-white font-medium rounded-xl transition-all duration-300 flex items-center justify-center space-x-2
                                 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/30">
                      <span>Iniciar sesión</span>
                      <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                      </svg>
                  </button>

                  <!-- Separador -->
                  <div class="relative my-8">
                      <div class="absolute inset-0 flex items-center">
                          <div class="w-full border-t border-gray-700/50"></div>
                      </div>
                      <div class="relative flex justify-center text-sm">
                          <span class="px-4 bg-transparent text-gray-400">o continúa con</span>
                      </div>
                  </div>

                  <!-- Botones de redes sociales -->
                  <div class="grid grid-cols-2 gap-4">
                      <button class="flex items-center justify-center px-4 py-2.5 border border-gray-700 rounded-xl hover:border-gray-500 
                                   transition-all duration-300 group">
                          <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                          </svg>
                      </button>
                      <button class="flex items-center justify-center px-4 py-2.5 border border-gray-700 rounded-xl hover:border-gray-500 
                                   transition-all duration-300 group">
                          <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/>
                          </svg>
                      </button>
                  </div>

                  <!-- Registro -->
                  <div class="text-center mt-6">
                      <p class="text-gray-400 text-sm">
                          ¿No tienes cuenta?
                          <a href="/register" class="text-blue-400 hover:text-blue-300 font-medium ml-1 transition-all duration-300 hover:tracking-wide">
                              Regístrate ahora
                          </a>
                      </p>
                  </div>
              </form>
          </div>

          <!-- Ventajas -->
          <div class="mt-8 grid grid-cols-2 gap-4">
              <div class="glass-effect rounded-xl p-4 flex items-center space-x-3 group hover:border-blue-500/30 transition-all duration-300">
                  <div class="p-2 bg-blue-500/10 rounded-lg group-hover:bg-blue-500/20 transition-colors duration-300">
                      <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                      </svg>
                  </div>
                  <div>
                      <h3 class="text-sm font-medium text-white">Conexión segura</h3>
                      <p class="text-xs text-gray-400">Protocolo SSL</p>
                  </div>
              </div>
              <div class="glass-effect rounded-xl p-4 flex items-center space-x-3 group hover:border-blue-500/30 transition-all duration-300">
                  <div class="p-2 bg-blue-500/10 rounded-lg group-hover:bg-blue-500/20 transition-colors duration-300">
                      <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                  </div>
                  <div>
                      <h3 class="text-sm font-medium text-white">Soporte 24/7</h3>
                      <p class="text-xs text-gray-400">Siempre disponible</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>