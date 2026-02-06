<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Accounting\BankAccountController;
use App\Http\Controllers\Accounting\ChartOfAccountController;
use App\Http\Controllers\CRM\ContactController;
use App\Http\Controllers\CRM\ContactGroupController;
use App\Http\Controllers\CRM\DealActivityController;
use App\Http\Controllers\CRM\DealController;
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
    Route::post('/branches/bulk', [BranchController::class, 'bulk'])->name('branches.bulk');

    // Bulk
   Route::get('/product-categories', [ProductCategoryController::class, 'index'])
        ->name('product-categories.index');

    Route::post('/product-categories', [ProductCategoryController::class, 'store'])
        ->name('product-categories.store');

    Route::put('/product-categories/{productCategory}', [ProductCategoryController::class, 'update'])
        ->name('product-categories.update');

    Route::delete('/product-categories/{productCategory}', [ProductCategoryController::class, 'destroy'])
        ->name('product-categories.destroy');

    Route::post('/product-categories/bulk', [ProductCategoryController::class, 'bulk'])
        ->name('product-categories.bulk');

    Route::get('/crm/contact-groups', [ContactGroupController::class, 'index'])
        ->name('crm.contact-groups.index');
    Route::post('/crm/contact-groups', [ContactGroupController::class, 'store'])
        ->name('crm.contact-groups.store');
    Route::put('/crm/contact-groups/{contactGroup}', [ContactGroupController::class, 'update'])
        ->name('crm.contact-groups.update');
    Route::delete('/crm/contact-groups/{contactGroup}', [ContactGroupController::class, 'destroy'])
        ->name('crm.contact-groups.destroy');
    Route::post('/crm/contact-groups/bulk', [ContactGroupController::class, 'bulk'])
        ->name('crm.contact-groups.bulk');

    Route::get('/crm/contacts', [ContactController::class, 'index'])
        ->name('crm.contacts.index');
    Route::post('/crm/contacts', [ContactController::class, 'store'])
        ->name('crm.contacts.store');
    Route::put('/crm/contacts/{contact}', [ContactController::class, 'update'])
        ->name('crm.contacts.update');
    Route::delete('/crm/contacts/{contact}', [ContactController::class, 'destroy'])
        ->name('crm.contacts.destroy');
    Route::post('/crm/contacts/bulk', [ContactController::class, 'bulk'])
        ->name('crm.contacts.bulk');

    Route::get('/crm/customers', [ContactController::class, 'index'])
        ->defaults('type', 'customer')
        ->name('crm.customers.index');
    Route::get('/crm/suppliers', [ContactController::class, 'index'])
        ->defaults('type', 'supplier')
        ->name('crm.suppliers.index');
    Route::get('/crm/leads', [ContactController::class, 'index'])
        ->defaults('type', 'lead')
        ->name('crm.leads.index');

    Route::get('/crm/deals', [DealController::class, 'index'])
        ->name('crm.deals.index');
    Route::post('/crm/deals', [DealController::class, 'store'])
        ->name('crm.deals.store');
    Route::put('/crm/deals/{deal}', [DealController::class, 'update'])
        ->name('crm.deals.update');
    Route::delete('/crm/deals/{deal}', [DealController::class, 'destroy'])
        ->name('crm.deals.destroy');
    Route::post('/crm/deals/bulk', [DealController::class, 'bulk'])
        ->name('crm.deals.bulk');

    Route::get('/crm/activity', [DealActivityController::class, 'index'])
        ->name('crm.activity.index');
    Route::post('/crm/activity', [DealActivityController::class, 'store'])
        ->name('crm.activity.store');
    Route::put('/crm/activity/{dealActivity}', [DealActivityController::class, 'update'])
        ->name('crm.activity.update');
    Route::delete('/crm/activity/{dealActivity}', [DealActivityController::class, 'destroy'])
        ->name('crm.activity.destroy');
    Route::post('/crm/activity/bulk', [DealActivityController::class, 'bulk'])
        ->name('crm.activity.bulk');

    Route::get('/accounting/chart-of-accounts', [ChartOfAccountController::class, 'index'])
        ->name('accounting.chart-of-accounts.index');
    Route::post('/accounting/chart-of-accounts', [ChartOfAccountController::class, 'store'])
        ->name('accounting.chart-of-accounts.store');
    Route::put('/accounting/chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'update'])
        ->name('accounting.chart-of-accounts.update');
    Route::delete('/accounting/chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'destroy'])
        ->name('accounting.chart-of-accounts.destroy');
    Route::post('/accounting/chart-of-accounts/bulk', [ChartOfAccountController::class, 'bulk'])
        ->name('accounting.chart-of-accounts.bulk');

    Route::get('/accounting/bank-accounts', [BankAccountController::class, 'index'])
        ->name('accounting.bank-accounts.index');
    Route::post('/accounting/bank-accounts', [BankAccountController::class, 'store'])
        ->name('accounting.bank-accounts.store');
    Route::put('/accounting/bank-accounts/{bankAccount}', [BankAccountController::class, 'update'])
        ->name('accounting.bank-accounts.update');
    Route::delete('/accounting/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])
        ->name('accounting.bank-accounts.destroy');
    Route::post('/accounting/bank-accounts/bulk', [BankAccountController::class, 'bulk'])
        ->name('accounting.bank-accounts.bulk');
});

require __DIR__.'/auth.php';
