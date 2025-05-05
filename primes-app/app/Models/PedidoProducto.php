<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Pedidos::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
