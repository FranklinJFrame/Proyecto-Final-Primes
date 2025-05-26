<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Pedidos;
use App\Models\User;

class Devolucion extends Model
{
    use HasFactory;

    protected $table = 'devoluciones'; // Especificar el nombre de la tabla si no sigue la convención de Laravel

    protected $fillable = [
        'pedido_id',
        'user_id',
        'motivo',
        'estado',
        'admin_notes',
        'imagenes_adjuntas',
    ];

    protected $casts = [
        'imagenes_adjuntas' => 'array',
    ];

    // Estados posibles de la devolución
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_APROBADA = 'aprobada';
    const ESTADO_RECHAZADA = 'rechazada';
    const ESTADO_RECIBIDO = 'recibido';

    /**
     * Get the pedido that owns the devolucion.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedidos::class);
    }

    /**
     * Get the user that owns the devolucion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the devolucion productos for the devolucion.
     */
    public function devolucionProductos(): HasMany
    {
        return $this->hasMany(DevolucionProducto::class);
    }
}
