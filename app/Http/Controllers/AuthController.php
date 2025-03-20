<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Connexion de l'utilisateur
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Déterminer la redirection selon le rôle
            $redirect = ($user->hasRole('admin')) ? '/admin/dashboard' : '/client/dashboard';
    
            return redirect($redirect);
        }
    
        return back()->withErrors(['error' => 'Identifiants incorrects']);
    }
    
    

    // Inscription d'un utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner un rôle (client par défaut)
        $user->assignRole('client');

       // Connexion automatique de l'utilisateur après inscription
Auth::login($user);

// Redirection vers le dashboard client avec son ID
return redirect()->route('clientdashboard', ['id' => $user->id]);

    }

    // Déconnexion
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    // Afficher le formulaire d'inscription
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    protected function registered(Request $request, $user)
{
    return redirect()->route('clientdashboard', ['id' => $user->id]);
}
}
