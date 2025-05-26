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
    public $telefono_usuario = ''; // For user's main phone
    public $mostrarFormularioTelefono = false; // Controls visibility of phone edit form
    public $telefono_nuevo = ''; // For the new phone number input
    
    // --- TARJETAS ---
    public $tarjeta_editId = null;
    public $nombre_tarjeta = '';
    public $numero_tarjeta = '';
    public $vencimiento = '';
    public $cvc = '';
    public $tipo_tarjeta = '';
    public $es_predeterminada = false;
    public $tarjeta_modo = 'crear';
    public $mostrarFormularioTarjeta = false;

    protected $rules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'direccion_calle' => 'required',
        'ciudad' => 'required',
        'estado' => 'required',
        'codigo_postal' => 'required',
        'telefono_usuario' => 'nullable|string|max:20', // Retained for updateUserProfile, if used for other fields
        'telefono_nuevo' => 'nullable|string|max:20', // Validation for the new phone input
    ];

    protected $tarjeta_rules = [
        'nombre_tarjeta' => 'required|min:3',
        'numero_tarjeta' => 'required|min:15|max:16',
        'vencimiento' => 'required|date_format:m/y',
        'cvc' => 'required|numeric|min:100|max:9999',
        'tipo_tarjeta' => 'required|in:visa,mastercard,amex',
    ];

    protected $tarjeta_messages = [
        'nombre_tarjeta.required' => 'El nombre en la tarjeta es obligatorio',
        'nombre_tarjeta.min' => 'El nombre debe tener al menos 3 caracteres',
        'numero_tarjeta.required' => 'El número de tarjeta es obligatorio',
        'numero_tarjeta.min' => 'El número de tarjeta debe tener al menos 15 dígitos',
        'numero_tarjeta.max' => 'El número de tarjeta no debe exceder 16 dígitos',
        'vencimiento.required' => 'La fecha de vencimiento es obligatoria',
        'vencimiento.date_format' => 'El formato debe ser MM/YY',
        'cvc.required' => 'El código CVC es obligatorio',
        'cvc.numeric' => 'El CVC debe ser numérico',
        'cvc.min' => 'El CVC debe tener al menos 3 dígitos',
        'cvc.max' => 'El CVC no debe exceder 4 dígitos',
        'tipo_tarjeta.required' => 'El tipo de tarjeta es obligatorio',
        'tipo_tarjeta.in' => 'Tipo de tarjeta no válido',
    ];

    public function mount()
    {
        $this->loadDirecciones();
        $this->loadTarjetas();
        $this->telefono_usuario = Auth::user()->telefono;
        $this->telefono_nuevo = Auth::user()->telefono;   // Initialize the phone edit form field
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

    public function resetTarjetaForm()
    {
        $this->tarjeta_editId = null;
        $this->nombre_tarjeta = '';
        $this->numero_tarjeta = '';
        $this->vencimiento = '';
        $this->cvc = '';
        $this->tipo_tarjeta = '';
        $this->es_predeterminada = false;
        $this->tarjeta_modo = 'crear';
        $this->mostrarFormularioTarjeta = false;
    }

    public function nuevaTarjeta()
    {
        if ($this->tarjetas->count() >= 3) {
            session()->flash('error', 'Solo puedes tener un máximo de 3 tarjetas registradas.');
            return;
        }
        $this->resetTarjetaForm();
        $this->mostrarFormularioTarjeta = true;
        $this->tarjeta_modo = 'crear';
    }

    public function saveTarjeta()
    {
        $this->validate($this->tarjeta_rules, $this->tarjeta_messages);
        if ($this->tarjetas->count() >= 3 && $this->tarjeta_modo === 'crear') {
            session()->flash('error', 'Solo puedes tener un máximo de 3 tarjetas registradas.');
            return;
        }
        // Si es la primera tarjeta, hacerla predeterminada
        if ($this->tarjetas->count() === 0) {
            $this->es_predeterminada = true;
        }
        // Si se marca como predeterminada, quitar predeterminada de las otras
        if ($this->es_predeterminada) {
            Auth::user()->tarjetas()->update(['es_predeterminada' => false]);
        }
        Auth::user()->tarjetas()->create([
            'nombre_tarjeta' => $this->nombre_tarjeta,
            'numero_tarjeta' => $this->numero_tarjeta,
            'vencimiento' => $this->vencimiento,
            'cvc' => $this->cvc,
            'tipo_tarjeta' => $this->tipo_tarjeta,
            'es_predeterminada' => $this->es_predeterminada,
        ]);
        $this->resetTarjetaForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta guardada correctamente.');
    }

    public function editTarjeta($id)
    {
        $tarjeta = Auth::user()->tarjetas()->findOrFail($id);
        $this->tarjeta_editId = $tarjeta->id;
        $this->nombre_tarjeta = $tarjeta->nombre_tarjeta;
        $this->numero_tarjeta = $tarjeta->numero_tarjeta;
        $this->vencimiento = $tarjeta->vencimiento;
        $this->cvc = $tarjeta->cvc;
        $this->tipo_tarjeta = $tarjeta->tipo_tarjeta;
        $this->es_predeterminada = $tarjeta->es_predeterminada;
        $this->tarjeta_modo = 'editar';
        $this->mostrarFormularioTarjeta = true;
    }

    public function updateTarjeta()
    {
        $this->validate($this->tarjeta_rules, $this->tarjeta_messages);
        $tarjeta = Auth::user()->tarjetas()->findOrFail($this->tarjeta_editId);
        if ($this->es_predeterminada && !$tarjeta->es_predeterminada) {
            Auth::user()->tarjetas()->where('id', '!=', $this->tarjeta_editId)->update(['es_predeterminada' => false]);
        }
        $tarjeta->update([
            'nombre_tarjeta' => $this->nombre_tarjeta,
            'numero_tarjeta' => $this->numero_tarjeta,
            'vencimiento' => $this->vencimiento,
            'cvc' => $this->cvc,
            'tipo_tarjeta' => $this->tipo_tarjeta,
            'es_predeterminada' => $this->es_predeterminada,
        ]);
        $this->resetTarjetaForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta actualizada correctamente.');
    }

    public function deleteTarjeta($id)
    {
        $tarjeta = Auth::user()->tarjetas()->findOrFail($id);
        // Si la tarjeta era predeterminada, hacer predeterminada la primera que quede
        if ($tarjeta->es_predeterminada && $this->tarjetas->count() > 1) {
            $primera_tarjeta = Auth::user()->tarjetas()->where('id', '!=', $id)->first();
            if ($primera_tarjeta) {
                $primera_tarjeta->update(['es_predeterminada' => true]);
            }
        }
        $tarjeta->delete();
        $this->resetTarjetaForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.mi-cuenta-page', [
            'direcciones' => $this->direcciones,
            'tarjetas' => $this->tarjetas,
        ]);
    }

    // Method to update user's profile information (e.g., phone)
    public function updateUserProfile()
    {
        $this->validate([
            'telefono_usuario' => 'nullable|string|max:20', 
            // Add other fields like 'nombre_usuario' if you add them to this form
        ]);

        $user = Auth::user();
        $user->telefono = $this->telefono_usuario;
        $user->save();

        session()->flash('success', 'Perfil actualizado correctamente.');
        $this->mostrarFormularioTelefono = false; // Optionally hide form on success
    }

    // Method specifically for the phone update form shown in the error
    public function updateTelefono()
    {
        $this->validate([
            'telefono_nuevo' => 'nullable|string|max:20', // Validation for the new phone input
        ]);

        $user = Auth::user();
        $user->telefono = $this->telefono_nuevo;
        $user->save();

        // Update telefono_usuario as well if you want them to be in sync immediately
        $this->telefono_usuario = $this->telefono_nuevo;

        session()->flash('success', 'Teléfono actualizado correctamente.');
        $this->mostrarFormularioTelefono = false; // Hide the phone edit form
    }
}
