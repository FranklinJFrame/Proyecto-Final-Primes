<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoReview extends Model
{
    use HasFactory;

    protected $table = 'producto_reviews';

    protected $fillable = [
        'producto_id',
        'user_id',
        'rating',
        'comentario',
        'aprobado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 