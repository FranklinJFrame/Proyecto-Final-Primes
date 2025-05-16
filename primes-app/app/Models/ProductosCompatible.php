<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Producto;

class ProductosCompatible extends Model
{
    use HasFactory;

    protected $table = 'productos_compatibles';

    protected $fillable = [
        'producto_id',
        'compatible_with_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function productoCompatible()
{
    return $this->belongsTo(\App\Models\Producto::class, 'compatible_with_id');
}
}
