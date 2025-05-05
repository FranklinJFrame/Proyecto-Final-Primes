<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedidos extends Model
{
    use  HasFactory;
    protected $fillable = [
        'user_id',
         'total_general',
        'metodo_pago',
        'estado_pago',
        'estado',
        'moneda',
        'costo_envio',
        'metodo_envio', 
        'notas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function productos()
    {
        return $this->hasMany(PedidoProductos::class);
    }

    public function direccion()
    {
        return $this->hasOne(Direccions::class);
    }
}
