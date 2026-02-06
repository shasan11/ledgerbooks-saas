<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Master\BranchController;
use App\Http\Controllers\Products\ProductCategoryController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');

    // Bulk
    Route::post('/branches/bulk', [BranchController::class, 'bulkStore'])->name('branches.bulkStore');
    Route::put('/branches/bulk', [BranchController::class, 'bulkUpdate'])->name('branches.bulkUpdate');
    Route::delete('/branches/bulk', [BranchController::class, 'bulkDestroy'])->name('branches.bulkDestroy');

    Route::get('/product-categories', [ProductCategoryController::class, 'index'])->name('productCategories.index');
    Route::post('/product-categories', [ProductCategoryController::class, 'store'])->name('productCategories.store');
    Route::put('/product-categories/{productCategory}', [ProductCategoryController::class, 'update'])->name('productCategories.update');
    Route::delete('/product-categories/{productCategory}', [ProductCategoryController::class, 'destroy'])->name('productCategories.destroy');

    // Bulk
    Route::post('/product-categories/bulk', [ProductCategoryController::class, 'bulkStore'])->name('productCategories.bulkStore');
    Route::put('/product-categories/bulk', [ProductCategoryController::class, 'bulkUpdate'])->name('productCategories.bulkUpdate');
    Route::delete('/product-categories/bulk', [ProductCategoryController::class, 'bulkDestroy'])->name('productCategories.bulkDestroy');
});

require __DIR__.'/auth.php';
