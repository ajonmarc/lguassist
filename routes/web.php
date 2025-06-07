<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('admin/dashboard', function () {
//     return Inertia::render('admin/Dashboard');
// })->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');

Route::middleware(['auth', 'citizen'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

   
}); 

Route::middleware(['auth', 'lgu'])->group(function () {
    Route::get('lgu/dashboard', function () {
        return Inertia::render('lgu/Dashboard');
    })->name('lgu.dashboard');

   
}); 

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        return Inertia::render('admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('admin/settings', function () {
        return Inertia::render('admin/Settings');
    })->name('admin.settings');
}); 

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
