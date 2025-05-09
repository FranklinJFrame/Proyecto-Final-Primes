<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedidos_id',
        'nombre',
        'apellido',
        'telefono',
        'direccion_calle', 
        'ciudad',
        'estado',
        'codigo_postal',
    ];

    public function pedido()
                            {
    return $this->belongsTo(Pedido::class); // sin S
}

    public function getFullNameAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getNombreCompletoAttribute(): string
{
    return "{$this->nombre} {$this->apellido}";
}
}

