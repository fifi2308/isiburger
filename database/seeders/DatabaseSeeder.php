<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Supprimer les utilisateurs existants pour éviter les doublons
        User::where('email', 'test@example.com')->delete();
        User::where('email', 'fatoufall0320@gmail.com')->delete();

        // Exécuter le seeder pour l'admin
        $this->call(AdminSeeder::class);

        // Créer un utilisateur de test (facultatif) si l'email n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password123')] // Utiliser un mot de passe sécurisé
        );

        // Créer un utilisateur client via le seeder
        $this->call(ClientsSeeder::class);
    }
}
