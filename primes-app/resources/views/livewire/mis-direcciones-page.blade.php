<div class="min-h-screen bg-gray-100 py-10">
  <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-2">
      <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
      Mis Direcciones
    </h2>
    <div class="mb-8">
      <form wire:submit.prevent="{{ $modo === 'crear' ? 'save' : 'update' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input wire:model.defer="nombre" type="text" placeholder="Nombre" class="input-form" required>
        <input wire:model.defer="apellido" type="text" placeholder="Apellido" class="input-form" required>
        <input wire:model.defer="telefono" type="text" placeholder="Teléfono" class="input-form" required>
        <input wire:model.defer="direccion_calle" type="text" placeholder="Dirección" class="input-form md:col-span-2" required>
        <input wire:model.defer="ciudad" type="text" placeholder="Ciudad" class="input-form" required>
        <select wire:model.defer="estado" class="input-form" required>
            <option value="">Selecciona una provincia</option>
            <option value="Distrito Nacional">Distrito Nacional</option>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Santiago">Santiago</option>
            <option value="La Vega">La Vega</option>
            <option value="San Cristóbal">San Cristóbal</option>
            <option value="Puerto Plata">Puerto Plata</option>
            <option value="Duarte">Duarte</option>
            <option value="La Romana">La Romana</option>
            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
            <option value="La Altagracia">La Altagracia</option>
            <option value="Peravia">Peravia</option>
            <option value="Azua">Azua</option>
            <option value="Barahona">Barahona</option>
            <option value="San Juan">San Juan</option>
            <option value="Monseñor Nouel">Monseñor Nouel</option>
            <option value="Monte Plata">Monte Plata</option>
            <option value="Valverde">Valverde</option>
            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
            <option value="Espaillat">Espaillat</option>
            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
            <option value="Samaná">Samaná</option>
            <option value="Bahoruco">Bahoruco</option>
            <option value="El Seibo">El Seibo</option>
            <option value="Hato Mayor">Hato Mayor</option>
            <option value="Independencia">Independencia</option>
            <option value="Pedernales">Pedernales</option>
            <option value="Elías Piña">Elías Piña</option>
            <option value="Monte Cristi">Monte Cristi</option>
            <option value="Dajabón">Dajabón</option>
            <option value="San José de Ocoa">San José de Ocoa</option>
            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
        </select>
        <input wire:model.defer="codigo_postal" type="text" placeholder="Código Postal" class="input-form" required>
        <div class="flex gap-2 mt-2 md:col-span-2">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 transition">{{ $modo === 'crear' ? 'Agregar' : 'Actualizar' }}</button>
          @if($modo === 'editar')
            <button type="button" wire:click="resetForm" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300 transition">Cancelar</button>
          @endif
        </div>
      </form>
    </div>
    <div>
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Tus direcciones guardadas</h3>
      @if($direcciones->isEmpty())
        <div class="text-gray-500 text-center py-8">No tienes direcciones guardadas.</div>
      @else
        <div class="flex flex-col gap-4">
          @foreach($direcciones as $dir)
            <div class="flex flex-col md:flex-row items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
              <div class="flex-1">
                <div class="font-bold text-gray-800">{{ $dir->nombre }} {{ $dir->apellido }}</div>
                <div class="text-gray-600 text-sm">{{ $dir->direccion_calle }}, {{ $dir->ciudad }}, {{ $dir->estado }}, {{ $dir->codigo_postal }}</div>
                <div class="text-gray-500 text-xs">Tel: {{ $dir->telefono }}</div>
              </div>
              <div class="flex gap-2 mt-4 md:mt-0">
                <button wire:click="edit({{ $dir->id }})" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">Editar</button>
                <button wire:click="delete({{ $dir->id }})" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Eliminar</button>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
  <style>
    .input-form {
      @apply px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 text-gray-800;
    }
  </style>
</div>
