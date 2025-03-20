<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirection après la connexion.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Après une authentification réussie, rediriger l'utilisateur en fonction de son rôle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
{
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');  // Redirection vers le dashboard admin
    } elseif ($user->role == 'client') {
        return redirect()->route('client.dashboard');  // Redirection vers le dashboard client
    }

    return redirect()->intended($this->redirectTo);
}

public function __construct()
{
    $this->middleware('guest')->except('logout');
}


}