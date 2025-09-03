<?php

use App\Models\Artist;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route model binding for Artist by username
Route::bind('artist', function ($value) {
    return Artist::where('username', $value)->firstOrFail();
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
