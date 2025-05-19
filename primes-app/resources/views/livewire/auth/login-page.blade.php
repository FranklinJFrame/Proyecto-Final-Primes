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
                          <div class="bg-red-500/80 text-white text-center rounded-lg py-2 px-4 mb-4 animate-pulse">
                              {{ $message }}
                          </div>
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
                      <a href="/forgot-password" class="text-sm text-blue-400 hover:text-blue-300 transition-all duration-300 hover:tracking-wide">
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

          <div wire:loading wire:target="login" class="flex justify-center items-center mb-4">
              <span class="text-blue-400 font-semibold animate-pulse">Procesando inicio de sesión...</span>
              <svg class="w-5 h-5 ml-2 text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
              </svg>
          </div>
      </div>
  </div>
</div>