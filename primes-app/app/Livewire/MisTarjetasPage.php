<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DatosTarj;

class MisTarjetasPage extends Component
{
    public $tarjetas = [];
    public $editId = null;
    public $nombre_tarjeta = '';
    public $numero_tarjeta = '';
    public $vencimiento = '';
    public $cvc = '';
    public $tipo_tarjeta = '';
    public $es_predeterminada = false;
    public $modo = 'crear';
    public $mostrarFormulario = false;

    protected $rules = [
        'nombre_tarjeta' => 'required|min:3',
        'numero_tarjeta' => 'required|min:15|max:16',
        'vencimiento' => 'required|date_format:m/y',
        'cvc' => 'required|numeric|min:100|max:9999',
        'tipo_tarjeta' => 'required|in:visa,mastercard,amex',
    ];

    protected $messages = [
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
        $this->loadTarjetas();
    }

    public function loadTarjetas()
    {
        $this->tarjetas = Auth::user()->tarjetas()->get();
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->nombre_tarjeta = '';
        $this->numero_tarjeta = '';
        $this->vencimiento = '';
        $this->cvc = '';
        $this->tipo_tarjeta = '';
        $this->es_predeterminada = false;
        $this->modo = 'crear';
        $this->mostrarFormulario = false;
    }

    public function nuevaTarjeta()
    {
        if ($this->tarjetas->count() >= 3) {
            session()->flash('error', 'Solo puedes tener un máximo de 3 tarjetas registradas.');
            return;
        }
        
        $this->resetForm();
        $this->mostrarFormulario = true;
        $this->modo = 'crear';
    }

    public function save()
    {
        $this->validate();
        
        if ($this->tarjetas->count() >= 3 && $this->modo === 'crear') {
            session()->flash('error', 'Solo puedes tener un máximo de 3 tarjetas registradas.');
            return;
        }

        // Check if card number already exists in the system
        $existingCard = DatosTarj::where('numero_tarjeta', $this->numero_tarjeta)->first();
        if ($existingCard && ($this->modo === 'crear' || ($this->editId !== null && $existingCard->id !== $this->editId))) {
            session()->flash('error', 'Este número de tarjeta ya está registrado en el sistema.');
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
        
        $this->resetForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta guardada correctamente.');
    }

    public function edit($id)
    {
        $tarjeta = Auth::user()->tarjetas()->findOrFail($id);
        $this->editId = $tarjeta->id;
        $this->nombre_tarjeta = $tarjeta->nombre_tarjeta;
        $this->numero_tarjeta = $tarjeta->numero_tarjeta;
        $this->vencimiento = $tarjeta->vencimiento;
        $this->tipo_tarjeta = $tarjeta->tipo_tarjeta;
        $this->es_predeterminada = $tarjeta->es_predeterminada;
        $this->modo = 'editar';
        $this->mostrarFormulario = true;
    }

    public function update()
    {
        $this->validate();
        
        // Check if card number already exists in the system, excluding the current card being edited
        $existingCard = DatosTarj::where('numero_tarjeta', $this->numero_tarjeta)->where('id', '!=', $this->editId)->first();
        if ($existingCard) {
            session()->flash('error', 'Este número de tarjeta ya está registrado en el sistema.');
            return;
        }
        
        $tarjeta = Auth::user()->tarjetas()->findOrFail($this->editId);

        // Si se marca como predeterminada, quitar predeterminada de las otras
        if ($this->es_predeterminada && !$tarjeta->es_predeterminada) {
            Auth::user()->tarjetas()->where('id', '!=', $this->editId)->update(['es_predeterminada' => false]);
        }
        
        $tarjeta->update([
            'nombre_tarjeta' => $this->nombre_tarjeta,
            'numero_tarjeta' => $this->numero_tarjeta,
            'vencimiento' => $this->vencimiento,
            'cvc' => $this->cvc,
            'tipo_tarjeta' => $this->tipo_tarjeta,
            'es_predeterminada' => $this->es_predeterminada,
        ]);
        
        $this->resetForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta actualizada correctamente.');
    }

    public function delete($id)
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
        $this->resetForm();
        $this->loadTarjetas();
        session()->flash('success', 'Tarjeta eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.mis-tarjetas-page');
    }
} 