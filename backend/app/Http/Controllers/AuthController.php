<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilRecherche;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Bienvenue sur votre espace JobMatch AI !');
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Identifiants invalides. Veuillez vérifier votre email et mot de passe.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Initialisation d'un profil de recherche vierge par défaut
        ProfilRecherche::create([
            'user_id' => $user->id,
            'types_contrat' => ['CDI'],
            'localisations' => ['Télétravail', 'France'],
            'salaire_min' => null,
            'keywords_must' => [],
            'keywords_excluded' => [],
            'teletravail_only' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('profile.show')->with('success', 'Compte créé avec succès ! Veuillez téléverser votre premier CV pour activer le matching IA.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
