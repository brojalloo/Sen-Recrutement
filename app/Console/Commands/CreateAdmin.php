<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {email : Adresse email du compte administrateur}
                            {--password= : Mot de passe (12 caractères minimum)}
                            {--name= : Nom affiché}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Créer un compte administrateur (seul chemin de création d'un admin)";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->option('password') ?: $this->secret('Mot de passe (12 caractères minimum)');
        $name = $this->option('name') ?: 'Administrateur';

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:12'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $admin = new User;
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = $password; // cast 'hashed' appliqué
        $admin->role = 'admin';
        $admin->status = 'active';
        $admin->save();

        $this->info("Compte administrateur créé : {$admin->email}");

        return 0;
    }
}
