<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Redirection après l'inscription.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Après une inscription réussie, rediriger l'utilisateur en fonction de son rôle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        // Vérifiez si l'utilisateur est un admin ou un client
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard'); // Redirige l'admin vers son tableau de bord
        } elseif ($user->role == 'client') {
            return redirect()->route('client.dashboard'); // Redirige le client vers son tableau de bord
        }

        // Par défaut, rediriger vers la page d'accueil
        return redirect()->intended($this->redirectPath());
    }

    // Autres méthodes comme la validation et la création d'un utilisateur
}
