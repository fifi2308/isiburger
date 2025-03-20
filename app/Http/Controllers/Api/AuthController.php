<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'token' => $user->createToken('isiburger')->plainTextToken
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créez l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Utilisation de Hash::make() pour la sécurité
        ]);

        // Assignez un rôle (client par défaut)
        $user->assignRole('client'); // Par défaut, un client est créé

        return response()->json([
            'token' => $user->createToken('isiburger')->plainTextToken
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Afficher le formulaire d'inscription
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showLogoutForm()
    {
        return view('auth.logout');
    }
}
