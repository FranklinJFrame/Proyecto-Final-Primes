<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class Navbar extends Component
{
    #[Url]
    public $search = '';
    public $showSuggestions = false;
    public $suggestions = [];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->suggestions = Producto::where('esta_activo', 1)
                ->where(function($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                })
                ->select('nombre', 'slug', 'imagenes')
                ->limit(5)
                ->get();
            $this->showSuggestions = true;
        } else {
            $this->showSuggestions = false;
        }
    }

    public function selectSuggestion($slug)
    {
        $this->showSuggestions = false;
        return $this->redirect("/products/{$slug}");
    }

    public function redirectToProducts()
    {
        $this->showSuggestions = false;
        if ($this->search) {
            return $this->redirect("/products?search={$this->search}");
        }
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
