<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'nombre',
        'apellido',
        'telefono',
        'direccion_calle', // Cambiado de 'direccion' a 'direccion_calle' para coincidir con la migración
        'ciudad',
        'estado',
        'codigo_postal',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class); 
    }

    public function getFullNameAtribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
