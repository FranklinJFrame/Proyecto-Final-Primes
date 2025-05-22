<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedidos extends Model
{
    use HasFactory;
    
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
        'nombre',
        'apellido',
        'telefono',
        'direccion_calle',
        'ciudad',
        'estado_direccion',
        'codigo_postal',
        'stripe_payment_intent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productos(): HasMany
    {
        return $this->hasMany(PedidoProducto::class, 'pedido_id'); 
    }

    public function direccion(): HasOne
    {
        return $this->hasOne(Direccion::class);
    }
}
