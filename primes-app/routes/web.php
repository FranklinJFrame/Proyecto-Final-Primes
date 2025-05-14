<?php

use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;

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
