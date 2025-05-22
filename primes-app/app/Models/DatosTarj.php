<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DatosTarj extends Model
{
    use HasFactory;

    protected $table = 'datos_tarj';

    protected $fillable = [
        'user_id',
        'nombre_tarjeta',
        'numero_tarjeta',
        'vencimiento',
        'cvc',
        'tipo_tarjeta',
        'es_predeterminada'
    ];

    protected $hidden = [
        'cvc',
    ];

    protected $casts = [
        'es_predeterminada' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutador para enmascarar el número de tarjeta
    public function getNumeroTarjetaEnmascaradoAttribute()
    {
        $numero = $this->numero_tarjeta;
        return str_repeat('*', strlen($numero) - 4) . substr($numero, -4);
    }
} 