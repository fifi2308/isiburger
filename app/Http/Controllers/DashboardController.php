<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord en fonction du rôle de l'utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        // Récupère l'utilisateur authentifié
        $user = auth()->user();

        // Vérifie si l'utilisateur est authentifié et a le rôle 'admin'
        if ($user && $user->hasRole('admin')) {
            // Code pour l'admin
            return view('dashboard.admin'); // Vue spécifique pour l'admin
        } else {
            // Code pour les autres utilisateurs ou invités
            return view('dashboard.user'); // Vue pour les utilisateurs non-admins
        }
    }
}
