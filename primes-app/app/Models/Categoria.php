<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Producto;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'slug',
        'imagen',
        'esta_activa',
    ];


    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function categoriasCompatibles() {
        return $this->hasMany(CategoriasCompatible::class);
    }
}
