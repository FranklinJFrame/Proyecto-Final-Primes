<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'rol' => 'string',
    ];

    /**
     * Verifica si el usuario es admin
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verifica si el usuario es cliente
     */
    public function isCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedidos::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pagos::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, $this->email));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailTecnobox);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }

    public function carritoProductos(): HasMany
    {
        return $this->hasMany(\App\Models\CarritoProducto::class);
    }

    public function direccions(): HasMany
    {
        return $this->hasMany(\App\Models\Direccion::class);
    }

    public function tarjetas(): HasMany
    {
        return $this->hasMany(\App\Models\DatosTarj::class, 'user_id');
    }

    /**
     * Get the card details for the user.
     */
    public function datosTarjetas(): HasMany
    {
        return $this->hasMany(DatosTarj::class, 'user_id');
    }

    /**
     * Get the default card for the user.
     */
    public function defaultDatosTarjeta(): HasOne
    {
        return $this->hasOne(DatosTarj::class, 'user_id')
                    ->where('es_predeterminada', true)
                    ->with('metodoPago');
    }

    /**
     * Get any card for the user (fallback if no default).
     */
    public function anyDatosTarjeta(): HasOne
    {
        return $this->hasOne(DatosTarj::class, 'user_id')
                    ->latestOfMany()
                    ->with('metodoPago');
    }

    // Reseñas hechas por el usuario
    public function reviews(): HasMany
    {
        return $this->hasMany(\App\Models\ProductoReview::class);
    }
}
