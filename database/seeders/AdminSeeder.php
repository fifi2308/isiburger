<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Vérifier si le rôle 'admin' existe
        if (!Role::where('name', 'admin')->exists()) {
            // Créer le rôle 'admin' si il n'existe pas
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        // Vérifier si l'admin existe déjà avec cet email
        $admin = User::firstOrCreate(
            ['email' => 'fatoufall0320@gmail.com'], // Vérifie si l'email existe
            [
                'name' => 'fatou',
                'password' => bcrypt('fatoufall23'), // Utilisez un mot de passe sécurisé
                'is_admin' => true, 
            ]
        );

        // Assigner le rôle 'admin' si l'utilisateur a été créé
        $admin->assignRole('admin');
    }
}
