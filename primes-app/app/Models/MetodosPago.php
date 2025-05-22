<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodosPago extends Model
{
    protected $table = 'metodos_pago';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'codigo',
        'esta_activo',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
    ];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pagos::class, 'metodo_pago_id');
    }

    public function datosTarjetas(): HasMany
    {
        return $this->hasMany(DatosTarj::class, 'metodo_pago_id');
    }
} 