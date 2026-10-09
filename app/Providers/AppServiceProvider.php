<?php

namespace App\Providers;

use App\Models\CompanyIntroHero;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'components.layouts.app', 'home', 'components.company-intro-hero'], function ($view) {
            $companyHero = CompanyIntroHero::first();
            $view->with('companyHero', $companyHero);
        });
    }
}
