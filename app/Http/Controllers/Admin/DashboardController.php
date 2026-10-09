<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\QuotationRequest;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Tableau de bord général des opérations d'exportation
     */
    public function index(): View
    {
        $totalQuotations = QuotationRequest::count();
        $newInquiries = QuotationRequest::where('status', 'new')->count();
        $quotedDeals = QuotationRequest::whereIn('status', ['quoted', 'client_viewed'])->count();
        $confirmedDeals = QuotationRequest::where('status', 'confirmed')->count();
        $contractsCount = Contract::count();
        $productsCount = Product::count();
        $slidesCount = HeroSlide::count();

        // Calcul de la valeur totale du pipeline chiffré
        $totalPipelineValue = QuotationRequest::whereNotNull('total_amount')->sum('total_amount');

        // Répartition par pays importateurs
        $topCountries = QuotationRequest::select('country')
            ->selectRaw('count(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Dernières demandes reçues
        $recentQuotations = QuotationRequest::with(['items.product'])
            ->latest('id')
            ->limit(8)
            ->get();

        // Activités récentes
        $recentActivities = class_exists(Activity::class)
            ? Activity::latest()->limit(6)->get()
            : collect();

        return view('admin.dashboard', compact(
            'totalQuotations',
            'newInquiries',
            'quotedDeals',
            'confirmedDeals',
            'contractsCount',
            'productsCount',
            'slidesCount',
            'totalPipelineValue',
            'topCountries',
            'recentQuotations',
            'recentActivities'
        ));
    }
}
