<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil et de changement de mot de passe
     */
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Mettre à jour les informations du profil (Nom et Email)
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', __('admin.profile_updated_success') ?? 'Profil mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe du compte connecté
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => __('admin.current_password_incorrect') ?? 'كلمة المرور الحالية غير صحيحة.',
            'password.confirmed' => __('admin.password_confirmation_mismatch') ?? 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => __('admin.password_min_chars') ?? 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', __('admin.password_changed_success') ?? 'تم تغيير كلمة المرور بنجاح.');
    }
}
