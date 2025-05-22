<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Direccion;
use App\Models\DatosTarj;

class MiCuentaPage extends Component
{
    public $direcciones = [];
    public $tarjetas = [];
    public $editId = null;
    public $nombre = '';
    public $apellido = '';
    public $telefono = '';
    public $direccion_calle = '';
    public $ciudad = '';
    public $estado = '';
    public $codigo_postal = '';
    public $modo = 'crear';
    public $mostrarFormulario = false;
    
    protected $rules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'telefono' => 'required',
        'direccion_calle' => 'required',
        'ciudad' => 'required',
        'estado' => 'required',
        'codigo_postal' => 'required',
    ];

    public function mount()
    {
        $this->loadDirecciones();
        $this->loadTarjetas();
    }

    public function loadDirecciones()
    {
        $this->direcciones = Auth::user()->direccions()->get();
    }

    public function loadTarjetas()
    {
        $this->tarjetas = Auth::user()->tarjetas()->get();
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
        $this->mostrarFormulario = false;
    }

    public function nuevaDireccion()
    {
        if ($this->direcciones->count() >= 3) {
            session()->flash('error', 'Solo puedes tener un máximo de 3 direcciones.');
            return;
        }
        
        $this->resetForm();
        $this->mostrarFormulario = true;
        $this->modo = 'crear';
    }

    public function save()
    {
        $this->validate();
        
        if ($this->direcciones->count() >= 3 && $this->modo === 'crear') {
            session()->flash('error', 'Solo puedes tener un máximo de 3 direcciones.');
            return;
        }
        
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
        session()->flash('success', 'Dirección guardada correctamente.');
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
        $this->mostrarFormulario = true;
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
        session()->flash('success', 'Dirección actualizada correctamente.');
    }

    public function delete($id)
    {
        $dir = Auth::user()->direccions()->findOrFail($id);
        $dir->delete();
        $this->resetForm();
        $this->loadDirecciones();
        session()->flash('success', 'Dirección eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.mi-cuenta-page', [
            'direcciones' => $this->direcciones,
            'tarjetas' => $this->tarjetas,
        ]);
    }
}
