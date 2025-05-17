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
Route::get('/forgot', ForgotPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);
Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);
