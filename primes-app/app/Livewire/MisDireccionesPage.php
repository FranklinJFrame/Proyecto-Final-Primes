<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Direccion;

class MisDireccionesPage extends Component
{
    public $direcciones = [];
    public $editId = null;
    public $nombre = '';
    public $apellido = '';
    public $telefono = '';
    public $direccion_calle = '';
    public $ciudad = '';
    public $estado = '';
    public $codigo_postal = '';
    public $modo = 'crear';

    protected $rules = [
        'nombre' => 'required|string|min:2|max:255',
        'apellido' => 'required|string|min:2|max:255',
        'telefono' => 'required|regex:/^[0-9\-\+\s\(\)]+$/|min:10|max:20',
        'direccion_calle' => 'required|string|min:5|max:255',
        'ciudad' => 'required|string|min:2|max:100',
        'estado' => 'required|in:Distrito Nacional,Santo Domingo,Santiago,La Vega,San Cristóbal,Puerto Plata,Duarte,La Romana,San Pedro de Macorís,La Altagracia,Peravia,Azua,Barahona,San Juan,Monseñor Nouel,Monte Plata,Valverde,Sánchez Ramírez,Espaillat,María Trinidad Sánchez,Hermanas Mirabal,Samaná,Bahoruco,El Seibo,Hato Mayor,Independencia,Pedernales,Elías Piña,Monte Cristi,Dajabón,San José de Ocoa,Santiago Rodríguez',
        'codigo_postal' => 'required|numeric|digits_between:4,6',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'apellido.required' => 'El apellido es obligatorio.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.regex' => 'El teléfono no es válido.',
        'direccion_calle.required' => 'La dirección es obligatoria.',
        'ciudad.required' => 'La ciudad es obligatoria.',
        'estado.required' => 'Debes seleccionar una provincia válida.',
        'estado.in' => 'La provincia seleccionada no es válida.',
        'codigo_postal.required' => 'El código postal es obligatorio.',
        'codigo_postal.numeric' => 'El código postal debe ser numérico.',
        'codigo_postal.digits_between' => 'El código postal debe tener entre 4 y 6 dígitos.',
    ];

    public function mount()
    {
        $this->loadDirecciones();
    }

    public function loadDirecciones()
    {
        $this->direcciones = Auth::user()->direccions()->get();
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->nombre = '';
        $this->apellido = '';
        $this->telefono = '';
        $this->direccion_calle = '';
        $this->ciudad = '';
        $this->estado = '';
        $this->codigo_postal = '';
        $this->modo = 'crear';
    }

    public function save()
    {
        $this->validate();
        Auth::user()->direccions()->create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'direccion_calle' => $this->direccion_calle,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
        ]);
        $this->resetForm();
        $this->loadDirecciones();
    }

    public function edit($id)
    {
        $dir = Auth::user()->direccions()->findOrFail($id);
        $this->editId = $dir->id;
        $this->nombre = $dir->nombre;
        $this->apellido = $dir->apellido;
        $this->telefono = $dir->telefono;
        $this->direccion_calle = $dir->direccion_calle;
        $this->ciudad = $dir->ciudad;
        $this->estado = $dir->estado;
        $this->codigo_postal = $dir->codigo_postal;
        $this->modo = 'editar';
    }

    public function update()
    {
        $this->validate();
        $dir = Auth::user()->direccions()->findOrFail($this->editId);
        $dir->update([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'direccion_calle' => $this->direccion_calle,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
        ]);
        $this->resetForm();
        $this->loadDirecciones();
    }

    public function delete($id)
    {
        $dir = Auth::user()->direccions()->findOrFail($id);
        $dir->delete();
        $this->resetForm();
        $this->loadDirecciones();
    }

    public function render()
    {
        return view('livewire.mis-direcciones-page');
    }
}
