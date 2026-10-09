<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$localePrefix = LaravelLocalization::setLocale();
if (app()->runningUnitTests() && empty($localePrefix)) {
    $localePrefix = 'en';
}

Route::group([
    'prefix' => $localePrefix,
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    // Vitrine d'accueil
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Moteur de Devis (RFQ)
    Route::get('/rfq', [QuotationController::class, 'create'])->name('rfq.create');
    Route::post('/rfq', [QuotationController::class, 'store'])->name('rfq.store');
    Route::get('/rfq/success/{reference}', [QuotationController::class, 'success'])->name('rfq.success');

    // Magic Link Interactif Client (Consultation, Négociation & Confirmation en 1 clic)
    Route::get('/quote/review/{reference}', [QuotationController::class, 'review'])->name('quote.review');
    Route::post('/quote/review/{reference}/confirm', [QuotationController::class, 'confirm'])->name('quote.confirm');
    Route::post('/quote/review/{reference}/modify', [QuotationController::class, 'modify'])->name('quote.modify');
});

/*
|--------------------------------------------------------------------------
| Rayanova T.D — Export Command Center (Back-Office Administration)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuotationManagementController;
use App\Http\Controllers\Admin\ContractManagementController;
use App\Http\Controllers\Admin\HeroSlideManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\CompanyHeroManagementController;

// Admin Auth & Locale
Route::prefix('admin')->group(function () {
    Route::get('/lang/{locale}', [AdminAuthController::class, 'setLocale'])->name('admin.setLocale');
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Back-Office Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Company Intro Hero CMS
        Route::get('/company-hero', [CompanyHeroManagementController::class, 'edit'])->name('admin.company_hero.edit');
        Route::put('/company-hero', [CompanyHeroManagementController::class, 'update'])->name('admin.company_hero.update');

        // Product Catalog CMS
        Route::get('/products', [ProductManagementController::class, 'index'])->name('admin.products.index');
        Route::get('/products/export/pdf', [ProductManagementController::class, 'exportCataloguePdf'])->name('admin.products.catalogue_pdf');
        Route::get('/products/create', [ProductManagementController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [ProductManagementController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{id}/edit', [ProductManagementController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{id}', [ProductManagementController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{id}', [ProductManagementController::class, 'destroy'])->name('admin.products.destroy');

        // Deals & Quotation Pricing Desk
        Route::get('/quotations', [QuotationManagementController::class, 'index'])->name('admin.quotations.index');
        Route::get('/quotations/{id}', [QuotationManagementController::class, 'show'])->name('admin.quotations.show');
        Route::post('/quotations/{id}/price', [QuotationManagementController::class, 'price'])->name('admin.quotations.price');
        Route::delete('/quotations/{id}', [QuotationManagementController::class, 'destroy'])->name('admin.quotations.destroy');

        // International Sales Contract & Invoicing
        Route::get('/quotations/{id}/contract/create', [ContractManagementController::class, 'create'])->name('admin.contracts.create');
        Route::post('/quotations/{id}/contract', [ContractManagementController::class, 'store'])->name('admin.contracts.store');
        Route::get('/contracts/{id}/pdf', [ContractManagementController::class, 'downloadPdf'])->name('admin.contracts.download');
        Route::get('/invoices/{id}/pdf', [ContractManagementController::class, 'downloadInvoicePdf'])->name('admin.invoices.download');

        // Hero Slider CMS
        Route::get('/slides', [HeroSlideManagementController::class, 'index'])->name('admin.slides.index');
        Route::get('/slides/create', [HeroSlideManagementController::class, 'create'])->name('admin.slides.create');
        Route::post('/slides', [HeroSlideManagementController::class, 'store'])->name('admin.slides.store');
        Route::get('/slides/{id}/edit', [HeroSlideManagementController::class, 'edit'])->name('admin.slides.edit');
        Route::put('/slides/{id}', [HeroSlideManagementController::class, 'update'])->name('admin.slides.update');
        Route::delete('/slides/{id}', [HeroSlideManagementController::class, 'destroy'])->name('admin.slides.destroy');
    });
});
