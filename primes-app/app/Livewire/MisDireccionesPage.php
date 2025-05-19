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
        $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'direccion_calle' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'codigo_postal' => 'required',
        ]);
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
        $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'direccion_calle' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'codigo_postal' => 'required',
        ]);
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
