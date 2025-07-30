<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes - Version Ultra-Simplifiée
|--------------------------------------------------------------------------
*/

// ===== PAGE D'ACCUEIL =====
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('home');
})->name('home');

// ===== AUTHENTIFICATION =====

// Page de connexion
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('login');
})->name('login');

// Traitement de la connexion
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'Connexion réussie !');
    }

    return back()->withErrors([
        'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    ])->withInput($request->only('email'));
});

// Page d'inscription
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('register');
})->name('register');

// Traitement de l'inscription
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'required|accepted'
    ], [
        'name.required' => 'Le nom est obligatoire.',
        'email.required' => 'L\'adresse e-mail est obligatoire.',
        'email.email' => 'L\'adresse e-mail doit être valide.',
        'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.'
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-vérification pour simplifier
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Compte créé avec succès !');
    } catch (\Exception $e) {
        return back()->withErrors([
            'email' => 'Une erreur est survenue lors de la création du compte.'
        ])->withInput($request->only('name', 'email'));
    }
});

// Déconnexion
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success', 'Déconnexion réussie !');
})->name('logout');

// ===== ROUTES PROTÉGÉES =====

Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Cards
    Route::get('/cards', function () {
        return view('cards');
    })->name('cards');

    // Analytics
    Route::get('/analytics', function () {
        return view('analytics');
    })->name('analytics');

    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

});

// ===== REDIRECTIONS =====
Route::get('/home', function () {
    return redirect('/dashboard');
});

// ===== API BASIQUE (pour les modals de cartes) =====
Route::middleware('auth')->prefix('api')->group(function () {
    
    // Endpoint simple pour tester
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    // Stats du dashboard
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'balance' => '€4,790.05',
            'income' => '€3,240.00',
            'expenses' => '€2,185.50',
            'savings' => '€1,054.50'
        ]);
    });
    
});

// ===== PAGES D'ERREUR =====
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});