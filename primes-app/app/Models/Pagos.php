<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DatosTarj;

class Pagos extends Model
{
    protected $fillable = [
        'pedido_id',
        'user_id',
        'metodo_pago_id',
        'estado',
        'monto',
        'moneda',
        'detalles',
        'referencia',
    ];

    protected $casts = [
        'detalles' => 'array',
        'monto' => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodosPago::class, 'metodo_pago_id');
    }

    public function tarjeta()
    {
        return $this->belongsTo(DatosTarj::class, 'user_id', 'user_id');
    }
}
