<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categoria;

class CategoriasCompatible extends Model
{
    use HasFactory;

    protected $table = 'categorias_compatibles';

    protected $fillable = [
        'categoria_id',
        'compatible_category_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function categoriaCompatible()
    {
        return $this->belongsTo(Categoria::class, 'compatible_category_id');
    }
}
