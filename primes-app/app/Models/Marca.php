<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
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
}
