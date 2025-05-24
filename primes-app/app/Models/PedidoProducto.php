<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PedidoProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the devolucion productos for the pedido producto.
     */
    public function devolucionProductos(): HasMany
    {
        return $this->hasMany(DevolucionProducto::class, 'pedido_producto_id');
    }
}
