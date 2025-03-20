<?php

// database/seeders/ClientsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Vérifier si le rôle 'client' existe, sinon le créer
        $roleClient = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // Créer un utilisateur client
        $client = User::create([
            'name' => 'fatou',  // Remplacez par le nom du client
            'email' => 'fatou23fall2003@gmail.com',  // Remplacez par l'email du client
            'password' => bcrypt('fatou'),  // Remplacez par le mot de passe du client
        ]);

        // Assigner le rôle 'client' à cet utilisateur
        $client->assignRole($roleClient);
    }
}
