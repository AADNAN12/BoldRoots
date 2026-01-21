<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users with their roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('roles')->get();

        if ($users->isEmpty()) {
            $this->info('Aucun utilisateur trouvé.');
            return 0;
        }

        $headers = ['ID', 'Nom', 'Email', 'Rôles', 'Actif', 'Dernière connexion'];
        $rows = [];

        foreach ($users as $user) {
            $roles = $user->getRoleNames()->implode(', ') ?: 'Aucun rôle';
            $isActive = $user->is_active ? 'Oui' : 'Non';
            $lastLogin = $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais';

            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $roles,
                $isActive,
                $lastLogin
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
