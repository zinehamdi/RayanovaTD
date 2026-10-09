<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Affiche l'écran de connexion au Back-Office
     */
    public function showLogin(): View
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Traite l'authentification de l'administrateur
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Vérifie que l'utilisateur a bien un rôle autorisé (Super Admin ou Sales Admin)
            $user = Auth::user();
            if (!$user->hasAnyRole(['super_admin', 'sales_admin'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access restricted to authorized export trade personnel.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to Rayanova T.D Export Command Desk, ' . $user->name);
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid trade desk credentials provided.',
        ]);
    }

    /**
     * Déconnexion sécurisée
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'Logged out successfully.');
    }

    /**
     * Bascule la langue du Dashboard (ar, en, fr)
     */
    public function setLocale(string $locale): RedirectResponse
    {
        if (in_array($locale, ['ar', 'en', 'fr'])) {
            session(['admin_locale' => $locale]);
        }

        return redirect()->back();
    }
}

