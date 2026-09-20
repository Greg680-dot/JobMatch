<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\AuthController;

// 1. Page d'Accueil Publique (Vitrine)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// 2. Routes Invités (Connexion & Inscription)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 4. Espace Candidat Protégé (Authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/matches/{match}/status', [DashboardController::class, 'updateMatchStatus'])->name('matches.status');

    Route::get('/profil', [CVController::class, 'show'])->name('profile.show');
    Route::post('/cv/upload', [CVController::class, 'upload'])->name('cv.upload');
    Route::post('/profil/preferences', [CVController::class, 'updatePreferences'])->name('profile.preferences.update');

    Route::post('/opportunites/collect', [OpportunityController::class, 'collect'])->name('opportunites.collect');

    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/opportunite/{opportunite}', [CandidatureController::class, 'generate'])->name('candidatures.generate');
    Route::put('/candidatures/{candidature}', [CandidatureController::class, 'update'])->name('candidatures.update');
    Route::post('/candidatures/{candidature}/status', [CandidatureController::class, 'updateStatus'])->name('candidatures.status');
});
