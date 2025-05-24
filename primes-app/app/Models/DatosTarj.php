<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatosTarj extends Model
{
    use HasFactory;

    protected $table = 'datos_tarj';

    protected $fillable = [
        'user_id',
        'metodo_pago_id',
        'nombre_tarjeta',
        'numero_tarjeta',
        'cvc',
        'vencimiento',
        'alias',
        'es_predeterminada',
    ];

    protected $hidden = [
        'cvc',
    ];

    protected $casts = [
        'es_predeterminada' => 'boolean',
    ];

    /**
     * Get the user that owns the card data.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    /**
     * Accessor para obtener solo los últimos 4 dígitos del número de tarjeta.
     * Esto NO evita que el número completo esté en la base de datos.
     * Es solo para visualización más segura.
     */
    public function getNumeroTarjetaOfuscadoAttribute(): ?string
    {
        if ($this->numero_tarjeta) {
            return '**** **** **** ' . substr($this->numero_tarjeta, -4);
        }
        return null;
    }

    /**
     * Accessor para el tipo de tarjeta (basado en el nombre o alias).
     * Puedes expandir esto si tienes una forma más concreta de determinar el tipo.
     */
    public function getTipoTarjetaAttribute(): ?string
    {
        // Prioriza el nombre del método de pago si la relación está cargada y existe
        if ($this->metodoPago) {
            return $this->metodoPago->nombre;
        }
        // Fallback al alias o nombre en tarjeta si no hay método de pago específico
        return $this->alias ?? $this->nombre_tarjeta ?? 'Tarjeta Desconocida';
    }
} 