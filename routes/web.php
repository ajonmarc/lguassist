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


    Route::get('user/form', [App\Http\Controllers\ClientController::class, 'userForm'])->name('user.form');


   
}); 

Route::middleware(['auth', 'lgu'])->group(function () {
    Route::get('lgu/dashboard', function () {
        return Inertia::render('lgu/Dashboard');
    })->name('lgu.dashboard');

   
}); 

Route::middleware(['auth', 'admin'])->group(function () {
  
    Route::get('admin/dashboard', [App\Http\Controllers\AdminController::class, 'adminDashboard'])->name('admin.dashboard');


    Route::get('admin/users', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.users');
    Route::post('admin/users', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.users.store');
    Route::put('admin/users/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('admin/users/{user}', [App\Http\Controllers\AdminController::class, 'users_destroy'])->name('admin.users.destroy');
    Route::patch('admin/users/{id}/role', [App\Http\Controllers\AdminController::class, 'updateRole'])->name('admin.users.updateRole');


    Route::get('admin/assistance_list', [App\Http\Controllers\AdminController::class, 'assistance_list'])->name('admin.assistance_list');


    

    Route::get('admin/assistance_form', [App\Http\Controllers\AdminController::class, 'assistance_form'])->name('admin.assistance_form');
    Route::get('admin/feedback', [App\Http\Controllers\AdminController::class, 'feedback'])->name('admin.feedback');
    Route::get('admin/service_experience', [App\Http\Controllers\AdminController::class, 'service_experience'])->name('admin.service_experience');
    Route::get('admin/about', [App\Http\Controllers\AdminController::class, 'about'])->name('admin.about');
    

    Route::get('admin/assistance/create', [App\Http\Controllers\AdminController::class, 'assistance_create'])->name('admin.assistance_create');
    Route::post('admin/assistance', [App\Http\Controllers\AdminController::class, 'assistance_store'])->name('admin.assistance.store');

    Route::get('admin/assistance/{assistance}', [App\Http\Controllers\AdminController::class, 'assistance_show'])->name('admin.assistance.show');

    




    Route::get('admin/settings', function () {
        return Inertia::render('admin/Settings');
    })->name('admin.settings');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
