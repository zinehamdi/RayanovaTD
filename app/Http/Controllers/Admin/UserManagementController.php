<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Protéger toutes les actions : Exclusif au Super Admin
     */
    public function __construct()
    {
        // Seul le Super Admin peut administrer les comptes
    }

    private function checkSuperAdmin(): void
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403, 'Accès réservé exclusivement au Super Admin.');
    }

    /**
     * Liste des administrateurs et membres de l'équipe
     */
    public function index(): View
    {
        $this->checkSuperAdmin();

        $users = User::with('roles')->orderBy('id', 'asc')->get();
        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Formulaire d'ajout d'un nouvel administrateur
     */
    public function create(): View
    {
        $this->checkSuperAdmin();

        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel administrateur avec son rôle spécifique
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.user_created_success') ?? 'Administrateur ajouté avec succès.');
    }

    /**
     * Formulaire de modification d'un administrateur
     */
    public function edit(int $id): View
    {
        $this->checkSuperAdmin();

        $user = User::with('roles')->findOrFail($id);
        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', Password::min(8)],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Mettre à jour le rôle (empêcher le super admin actuel de se retirer son propre rôle super_admin)
        if ($user->id === auth()->id() && $validated['role'] !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas révoquer votre propre rôle Super Admin.');
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.user_updated_success') ?? 'Administrateur mis à jour avec succès.');
    }

    /**
     * Supprimer un administrateur
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        // Protection : On ne peut pas se supprimer soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('admin.cannot_delete_self') ?? 'Impossible de supprimer votre propre compte connecté.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('admin.user_deleted_success') ?? 'Compte supprimé avec succès.');
    }
}
