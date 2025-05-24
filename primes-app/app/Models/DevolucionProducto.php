<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Asegúrate de que la ruta a PedidoProducto sea correcta
use App\Models\PedidoProducto;

class DevolucionProducto extends Model
{
    use HasFactory;

    protected $table = 'devolucion_productos'; // Especificar el nombre de la tabla

    protected $fillable = [
        'devolucion_id',
        'pedido_producto_id',
        'cantidad_devuelta',
    ];

    /**
     * Get the devolucion that owns the devolucion producto.
     */
    public function devolucion(): BelongsTo
    {
        return $this->belongsTo(Devolucion::class);
    }

    /**
     * Get the pedido producto associated with the devolucion producto.
     */
    public function pedidoProducto(): BelongsTo
    {
        return $this->belongsTo(PedidoProducto::class);
    }
}
