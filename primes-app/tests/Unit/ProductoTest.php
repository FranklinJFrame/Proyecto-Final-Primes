<?php

namespace Tests\Unit;

use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_ser_creado_con_los_campos_requeridos()
    {
        $producto = Producto::create([
            'nombre' => 'Celular Samsung',
            'descripcion' => 'Un celular de prueba',
            'precio' => 1000,
            'moneda' => 'USD',
            'cantidad' => 10,
            'en_stock' => true,
            'esta_activo' => true,
            'es_destacado' => false,
            'en_oferta' => false,
            'es_devolucible' => true,
        ]);
        $this->assertInstanceOf(Producto::class, $producto);
        $this->assertTrue($producto->en_stock);
        $this->assertTrue($producto->es_devolucible);
    }

    public function test_genera_slug_automaticamente_si_no_se_provee()
    {
        $producto = Producto::create([
            'nombre' => 'Celular Xiaomi',
            'descripcion' => 'Otro celular',
            'precio' => 800,
            'moneda' => 'USD',
            'cantidad' => 5,
            'en_stock' => true,
            'esta_activo' => true,
            'es_destacado' => false,
            'en_oferta' => false,
            'es_devolucible' => true,
        ]);
        $this->assertEquals(Str::slug('Celular Xiaomi'), $producto->slug);
    }
} 