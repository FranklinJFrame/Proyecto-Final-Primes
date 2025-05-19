<?php

use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;
use App\Livewire\ProductosPage;
use App\Livewire\CarritoPage;
use App\Livewire\DetalleProductoPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MisPedidosPage;
use App\Livewire\MiPedidosDetallePage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\FacturaController;
//primes-app\app\Livewire\HomePage.php
Route::get('/', HomePage::class);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductosPage::class);
Route::get('/cart', CarritoPage::class);
Route::get('/products/{slug}', DetalleProductoPage::class);
Route::get('/checkout', CheckoutPage::class);
Route::get('/my-orders', MisPedidosPage::class);
Route::get('/my-orders/{order}', MiPedidosDetallePage::class);
Route::get('/login', LoginPage::class);
Route::get('/register', RegisterPage::class);
Route::get('/forgot-password', ForgotPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);
Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);

Route::get('/categoria/{slug}', function ($slug) {
    return redirect('/products?categoria=' . $slug);
});

Route::get('/categories/{slug}', function ($slug) {
    return redirect('/products?categoria=' . $slug);
});

Route::get('/marca/{slug}', function ($slug) {
    return redirect('/products?marca=' . $slug);
});

Route::get('/brands/{slug}', function ($slug) {
    return redirect('/products?marca=' . $slug);
});

Route::middleware('auth')->group(function () {
    Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/add', [\App\Http\Controllers\CarritoController::class, 'add'])->name('carrito.add');
    Route::post('/carrito/remove', [\App\Http\Controllers\CarritoController::class, 'remove'])->name('carrito.remove');
    Route::post('/carrito/increment', [\App\Http\Controllers\CarritoController::class, 'increment'])->name('carrito.increment');
    Route::post('/carrito/decrement', [\App\Http\Controllers\CarritoController::class, 'decrement'])->name('carrito.decrement');
    Route::post('/carrito/clear', [\App\Http\Controllers\CarritoController::class, 'clear'])->name('carrito.clear');
});

Route::get('/factura/pdf/{pedido}', [FacturaController::class, 'descargar'])->name('factura.pdf');

// Admin pedidos detalle y factura
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/pedidos/{pedido}', [PedidoAdminController::class, 'show'])->name('admin.pedidos.show');
});
