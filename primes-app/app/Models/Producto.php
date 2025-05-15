<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\PedidoProducto;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagenes',
        'precio',
        'moneda',
        'categoria_id',
        'marca_id',
        'cantidad', // Asegurando que está incluido
    ];
    protected $casts = [
        'imagenes' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($producto) {
            if (empty($producto->slug)) {
                $producto->slug = \Illuminate\Support\Str::slug($producto->nombre);
            }
        });

        static::updating(function ($producto) {
            if (empty($producto->slug)) {
                $producto->slug = \Illuminate\Support\Str::slug($producto->nombre);
            }
        });
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function pedido_productos()
    {
        return $this->hasMany(PedidoProducto::class);
    }
}
