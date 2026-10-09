<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\ProductCertificate;
use App\Models\CompanyIntroHero;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Affiche la vitrine d'accueil internationale Rayanova T.D
     */
    public function index(): View
    {
        $companyHero = CompanyIntroHero::first();

        $slides = HeroSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::with(['category', 'certificates'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $certificates = ProductCertificate::all();

        return view('home', compact('companyHero', 'slides', 'categories', 'products', 'certificates'));
    }
}
