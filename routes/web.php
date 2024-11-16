<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePanelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [CategoryController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Service Panel Route
    Route::get('/ServicesPanel', [ServicePanelController::class, 'index'])->name('services.panel');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Service Panel Route (GET)
    Route::get('/service-panel', [ServicePanelController::class, 'index'])->name('service.panel');
    
    // Add Service (POST)
    Route::post('/service-panel/services', [ServicePanelController::class, 'store'])->name('services.store');
    Route::put('/service-panel/{id}', [ServicePanelController::class, 'update'])->name('services.update');
});




Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/categories', [DashboardController::class, 'store'])->name('dashboard.categories.store');
    Route::put('/dashboard/categories/{id}', [DashboardController::class, 'update'])->name('dashboard.categories.update');
    Route::delete('/dashboard/categories/{id}', [DashboardController::class, 'destroy'])->name('dashboard.categories.destroy');
});


Route::get('/categories/{id}/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
