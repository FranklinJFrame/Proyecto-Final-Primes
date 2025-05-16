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
        'cantidad',
        'en_stock',
        'esta_activo',
        'es_destacado',
        'en_oferta',
        'es_devolucible', // Nuevo campo añadido
    ];
    
    protected $casts = [
        'imagenes' => 'array',
        'en_stock' => 'boolean',
        'esta_activo' => 'boolean',
        'es_destacado' => 'boolean',
        'en_oferta' => 'boolean',
        'es_devolucible' => 'boolean', // Nuevo campo añadido con cast a boolean
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

    public function productosCompatibles()
    {
        return $this->hasMany(ProductosCompatible::class);
    }
}
