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
        'categoria_id',
        'marca_id',
        'nombre',
        'slug',
        'imagenes',
        'descripcion',
        'precio',
        'esta_activo',
        'es_destacado',
        'en_stock',
        'en_oferta',
    ];
    protected $casts = [
        'imagenes' => 'array',
    ];

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
